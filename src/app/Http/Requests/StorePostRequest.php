<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
{
    /**
     * このリクエストを実行する権限があるか
     */
    public function authorize(): bool
    {
        // 認証済みユーザーなら OK
        return Auth::check();
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            'content' => [
                'required',
                'string',
                'max:500',
            ],

            // 画像は任意、2MB以下
            'image' => [
                'nullable',
                'image',
                'max:2048',
            ],

            // タグ入力（カンマ・スペース区切りの文字列）
            'tags' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * エラーメッセージ
     */
    public function messages(): array
    {
        return [
            'content.required' => '投稿内容は必須です。',
            'content.max' => '投稿内容は500文字以内で入力してください。',

            'image.image' => '画像ファイルを選択してください。',
            'image.max' => '画像サイズは2MB以内にしてください。',

            'tags.max' => 'タグは255文字以内で入力してください。',
        ];
    }

    /**
     * バリデーション前に値を整形
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('tags')) {

            // 連続スペースを 1 つに
            // 前後の空白除去
            // 全角スペースも処理
            $clean = preg_replace('/\s+/u', ' ', $this->tags ?? '');

            $this->merge([
                'tags' => trim($clean),
            ]);
        }
    }
}
