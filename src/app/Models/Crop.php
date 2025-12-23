// app/Models/Crop.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // 追加

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        // その他の作物情報カラム
    ];

    /**
     * この作物を「育てている」ユーザー (中間テーブル user_crops 経由)
     */
    public function users(): BelongsToMany
    {
        // 第二引数で中間テーブル名を指定
        return $this->belongsToMany(User::class, 'user_crops');
    }
}
