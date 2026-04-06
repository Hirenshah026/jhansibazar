<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\WhatsappAccountManager;

class SendCouponsReminder extends Command
{
    protected $signature = 'reminder:send-coupons';
    protected $description = 'Send WhatsApp coupon reminders for today';

    public function handle()
    {
        $now = now()->format('Y-m-d H:i:00'); // e.g., "2025-07-07 12:58:00"

        $reminders = DB::table('coupons_reminder')
            ->where('send_allow', 1)
            ->where('send_status', 0)
            ->where('send_date', '=', $now)
            ->get();


        foreach ($reminders as $reminder) {
            // Example message
            $message = "Hi! Use your coupon code '{$reminder->coupon_code}' today. Don't miss out!";

            // Assume mobile number is stored in user or in the reminder record
            $user = DB::table('user')->where('id', $reminder->user_id)->first();

            if (!$user || !$user->mobile) {
                $this->error("mobile not found for user ID: {$reminder->user_id}");
                continue;
            }
            $wh_temp = DB::table('whatsapp_template')->where('template_id', $reminder->template_id??0)->first();
            
            $inv = $reminder->coupon_code ?? 0;
            $mess = $wh_temp??[];
            $shkid = $reminder->shopkeeper_id ?? 0;
            $uid = $reminder->user_id ?? 0;
            $mobile=$user->mobile??0;
            $sent_id=rand(40000,99999999);
            $sent = $this->sendWhatsappMessage($inv, $mess, $shkid, $uid,$reminder->coupon_code,$sent_id);

            if ($sent) {
                DB::table('coupons_reminder')
                    ->where('id', $reminder->id)
                    ->update([
                        'send_status' => 1,
                       'sent_id'=>$sent_id
                    ]);

                $this->info("WhatsApp sent to $mobile for coupon $reminder->coupon_code");
            } else {
                $this->error("Failed to send to $mobile");
            }
        }

        $this->info("Total reminders processed: " . $reminders->count());
    }

    protected function sendWhatsappMessage($inv, $message, $shkid, $uid,$code,$sent_id)
    {
        try {
            $user = DB::table('user')->where('id', $uid)->first();
            $wh_info = DB::table('whatsapp_account_shopowner')->where('shopkeeper_id', $shkid)->first();

            if (($wh_info->balance ?? 0) <= 0) {
                \Log::warning("Shopkeeper ID {$shkid} has insufficient balance.");
                return false;
            }

            $cop_del = DB::table('coupons')->where('code',$code??0)->orderByDesc('id')->first();
            $finalMessage = str_replace(
                ['[Customer Name]','[Coupon Code]','[Total Amount]','[Discount Amount]','[Expired Date]'],
                [ucwords($user->name??'Hi User'),$cop_del->code??00,$cop_del->min_amount??00,
                $cop_del->discount??0, 
                $cop_del->expiry_date??date('d-m-Y'),],
                $message->message??"Coupon Reminder"
            );

            $response = Http::get("https://web.cloudwhatsapp.com/wapp/api/send", [
                'apikey' => env('WHATSAPP_API_KEY'),
                'mobile' => $user->mobile ?? '0000000000',
                'msg' => $finalMessage,
                'img1'=>$message->img??null
            ]);

            $res = json_decode($response->body());

            // Log send history using controller-based manager
            (new WhatsappAccountManager)->whatsapp_message_send_history(
                $inv, $uid, $shkid,$finalMessage,
                'coupon auto reminder',
                ($cop_del->min_amount??0), $message->img??null,'Cron Automatic server send message',$sent_id
            );

            return $res->status ?? false;

        } catch (\Exception $e) {
            \Log::error("COP REM - WhatsApp send failed: " . $e->getMessage());
            return false;
        }
    }
}
