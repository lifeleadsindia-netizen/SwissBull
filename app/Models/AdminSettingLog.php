<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSettingLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_setting_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'table_name',
        'field_name',
        'old_value',
        'new_value',
        'changed_by',
        'ip_address',
    ];

    /**
     * Log multiple field changes at once by comparing old and new data arrays.
     *
     * @param  string  $tableName  Database table name (e.g. 'package_distributions')
     * @param  array<string, mixed>  $oldData  Field => old value
     * @param  array<string, mixed>  $newData  Field => new value
     * @param  string  $changedBy  Who made the change (default: 'admin')
     * @param  string|null  $ipAddress  IP address of the requester
     */
    public static function logChanges(
        string $tableName,
        array $oldData,
        array $newData,
        string $changedBy = 'admin',
        ?string $ipAddress = null
    ): void {
        foreach ($newData as $field => $newValue) {
            $oldValue = $oldData[$field] ?? null;

            // Only log if value actually changed
            if ((string) $oldValue !== (string) $newValue) {
                static::create([
                    'table_name' => $tableName,
                    'field_name' => $field,
                    'old_value' => $oldValue !== null ? (string) $oldValue : null,
                    'new_value' => $newValue !== null ? (string) $newValue : null,
                    'changed_by' => $changedBy,
                    'ip_address' => $ipAddress,
                ]);
            }
        }
    }
}
