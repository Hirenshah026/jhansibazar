<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\WhatsappAccountManager; // ✅ updated path

class SendWhatsappMessages extends Command
{
    protected $signature = 'whatsapp:send-messages';
    protected $description = 'Send WhatsApp messages where send_date is today and send_status is 0';

    public function handle()
    {
        $today = date('Y-m-d');
        $this->info("Today's date: $today");

        $latestRemainAmountIds = DB::table('user_product_entry_remain_amount')
        ->select(DB::raw('MAX(id) as id'))
        ->where('remain_amount', '>', 0)
        ->groupBy('user_id') 
        ->pluck('id');

       
        $messages = DB::table('whatsapp_message_send_list as w')
        ->join('user_product_entry_remain_amount as r', 'w.user_id', '=', 'r.user_id')
        ->whereDate('w.send_date', $today)
        ->where('w.send_status', 0)
        ->where('w.send_allow', 1)
        ->whereIn('r.id', $latestRemainAmountIds) 
        ->select('w.*', 'r.invoice_no', 'r.remain_amount', 'r.id as remain_id')
        ->get();

        $count = $messages->count();
        $this->info("Found {$count} message(s) to send.");

        if ($count === 0) {
            $this->info('No messages to send today.');
            return 0;
        }
        $setting = DB::table('settings')->where('id',11)->first();
        foreach ($messages as $m) {
            $this->info("Processing invoice_no: {$m->invoice_no}");

            $inv = $m->invoice_no ?? 0;
            $mess = $setting->value??'Invoice Message Cron Test';
            $shkid = $m->shopkeeper_id ?? 0;
            $uid = $m->user_id ?? 0;
            $sent_id=rand(40000,99999999);
            $success = $this->sendWhatsapp($inv, $mess, $shkid, $uid,$sent_id);

            if ($success) {
                DB::table('whatsapp_message_send_list')->where('id', $m->id)
                ->update(['send_status' => 1]);

                $wt_config = DB::table('whatsapp_invoice_config')->where('shopkeeper_id',$shkid??0)->first();

                $ids=DB::table('whatsapp_message_send_list')->insertGetId([                   
                    'invoice_no'=>$inv??0,
                    'send_date' => date('Y-m-d', strtotime(' +'.$wt_config->repeat_days.' days')),
                    'user_id' => $uid??0,
                    'send_status'=>0, 
                    'send_allow'=>$wt_config->send_allow??0,               
                    'shopkeeper_id'=>$shkid??0,
                    'sent_id'=>$sent_id
                ]);

                $this->info("✅ Message sent successfully for invoice_no: {$inv}");
            } else {
                $this->error("❌ Failed to send message for invoice_no: {$inv}");
            }
        }

        return 0;
    }

    private function sendWhatsapp($inv, $message, $shkid, $uid,$sent_id)
    {
        try {
            $user = DB::table('user')->where('id', $uid)->first();
            $wh_info = DB::table('whatsapp_account_shopowner')->where('shopkeeper_id', $shkid)->first();

            if (($wh_info->balance ?? 0) <= 0) {
                \Log::warning("Shopkeeper ID {$shkid} has insufficient balance.");
                return false;
            }

            $udhar_del = DB::table('user_product_entry_remain_amount')->where('invoice_no',$inv??0)->orderByDesc('id')->first();
            $finalMessage = str_replace(
                ['[Customer Name]','[Udhar Date]','[Total Amount]','[Udhar Amount]'],
                [ucwords($user->name??'Hi User'),date('d M Y',strtotime($udhar_del->entry_date??date('d-m-Y'))),($udhar_del->total_amount??0),($udhar_del->remain_amount??0)],
                $message??"Hi User"
            );

            $response = Http::get("https://web.cloudwhatsapp.com/wapp/api/send", [
                'apikey' => env('WHATSAPP_API_KEY'),
                'mobile' => $user->mobile ?? '0000000000',
                'msg' => $finalMessage
            ]);

            $res = json_decode($response->body());

            // Log send history using controller-based manager
            (new WhatsappAccountManager)->whatsapp_message_send_history(
                $inv, $uid, $shkid,$finalMessage,
                'udhar auto reminder',
                ($udhar_del->remain_amount??0), null,'Cron Automatic server send message',$sent_id
            );

            return $res->status ?? false;

        } catch (\Exception $e) {
            \Log::error("WhatsApp send failed: " . $e->getMessage());
            return false;
        }
    }
}
