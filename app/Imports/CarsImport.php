<?php

namespace App\Imports;

use App\Models\Advert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;

class CarsImport implements ToModel, WithHeadingRow, WithEvents, WithBatchInserts, WithChunkReading
{
    use Importable;

    protected $uniqueRows = [];

    public function model(array $row)
    {
        // Проверка на дубликаты в текущем импорте
        $uniqueKey = $this->generateUniqueKey($row);
        if (in_array($uniqueKey, $this->uniqueRows)) {
            return null;
        }

        $this->uniqueRows[] = $uniqueKey;

        // Удаление символов -, . и пробелов из строк 'number' и 'engine'
        $row['number'] = preg_replace('/[-.\s]/', '', $row['number'] ?? '');
        $row['engine'] = preg_replace('/[-.\s]/', '', $row['engine'] ?? '');

        // Проверка на существование товара в базе данных
        $existingAdvert = Advert::where('art_number', $row['art_number'])
            ->where('product_name', $row['product_name'])
            ->where('brand', $row['brand'])
            ->where('model', $row['model'])
            ->first();

        if ($existingAdvert) {
            return null;
        }

        return new Advert([
            'user_id' => auth()->id(), // Предполагается, что пользователь авторизован
            'art_number' => $row['art_number'],
            'product_name' => $row['product_name'],
            'new_used' => $row['new_used'] ?? null,
            'brand' => $row['brand'],
            'model' => $row['model'] ?? null,
            'body' => $row['body'] ?? null,
            'number' => $row['number'],
            'engine' => $row['engine'],
            'year' => $row['year'] ?? null,
            'L_R' => $row['L_R'] ?? null,
            'F_R' => $row['F_R'] ?? null,
            'U_D' => $row['U_D'] ?? null,
            'color' => $row['color'] ?? null,
            'applicability' => $row['applicability'] ?? null,
            'quantity' => $row['quantity'] ?? null,
            'price' => $row['price'],
            'availability' => $row['availability'] ?? null,
            'delivery_time' => $row['delivery_time'] ?? null,
            'photo' => $row['photo'] ?? null,
            'data' => now(), // Текущая дата и время
            'status_ad' => $row['status_ad'] ?? null,
            'id_ad' => $row['id_ad'] ?? null,
            'main_photo_url' => $row['main_photo_url'] ?? null,
            'additional_photo_url_1' => $row['additional_photo_url_1'] ?? null,
            'additional_photo_url_2' => $row['additional_photo_url_2'] ?? null,
            'additional_photo_url_3' => $row['additional_photo_url_3'] ?? null,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $this->uniqueRows = [];
            },
        ];
    }

    protected function generateUniqueKey(array $row): string
    {
        // Генерация уникального ключа на основе всех полей
        return md5(json_encode($row));
    }

    public function batchSize(): int
    {
        return 100; // Размер пакета для обработки
    }

    public function chunkSize(): int
    {
        return 100; // Размер чанка для чтения
    }
}