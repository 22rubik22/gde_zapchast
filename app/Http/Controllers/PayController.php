<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayController extends Controller
{
    public function pay(Request $request)
    {
        // Индификатор терминала.
        $TerminalKey = '1684504766185DEMO';
        
        // Сумма в рублях.
        $sum = 300;
        
        // Номер заказа.
        $order_id = 0;
        
        $data = array(
            "TerminalKey" => $TerminalKey,
            "Amount" => $sum * 100,
            "OrderId" => $order_id,
            "SuccessURL" => "https://pornhub.com",
            "PayType" => 'O',
        );
                                
        $ch = curl_init('https://securepay.tinkoff.ru/v2/Init');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE)); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);
        
        $res = json_decode($res, true);

        if (!empty($res['PaymentURL'])) {
            // Редирект в платёжную систему.
            return redirect($res['PaymentURL']);
        } else {
            // Обработка ошибки
            return back()->withErrors(['payment' => 'Ошибка инициализации платежа']);
        }
    }


    
   
 
        public function pay2(Request $request)
        {
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://business.tbank.ru/openapi/api/v1/invoice/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{"invoiceNumber":"string","dueDate":"2024-11-01","invoiceDate":"2024-11-01","accountNumber":"string","payer":{"name":"string","inn":"string","kpp":"string"},"items":[{"name":"string","price":0,"unit":"string","vat":"None","amount":0}],"contacts":[{"email":"string"}],"contactPhone":"string","comment":"string"}',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Bearer <TOKEN>'
                ),
            ));
    
            $response = curl_exec($curl);
    
            if ($response === false) {
                // Обработка ошибки cURL
                $error = curl_error($curl);
                curl_close($curl);
                return view('pay', ['error' => 'Ошибка при выполнении запроса: ' . $error]);
            }
    
            curl_close($curl);
    
            // Преобразуем JSON-ответ в массив
            $invoiceData = json_decode($response, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Обработка ошибки декодирования JSON
                return view('pay', ['error' => 'Ошибка при декодировании JSON: ' . json_last_error_msg()]);
            }
    
            // Передаем данные в представление
            return view('pay', ['invoiceData' => $invoiceData]);
        }
    }
    

