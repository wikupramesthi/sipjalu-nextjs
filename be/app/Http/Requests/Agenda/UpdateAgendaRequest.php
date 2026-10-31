<?php

namespace App\Http\Requests\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAgendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title'           => [
                'required',
                'string',
                'max:255',
                Rule::unique('agendas', 'title')->ignore($this->route('agenda')->uuid, 'uuid'),
            ],
            'excerpt'         => ['nullable', 'string', 'max:255'],
            'content'         => ['required', 'string'],
            'category_uuid'   => ['required', 'exists:categories,uuid'],
            'scheduled_at'    => ['nullable', 'date'],
            'jenis_agenda'    => ['required', 'in:offline,online'],
            'lokasi'          => ['nullable', 'string', 'max:255', 'required_if:jenis_agenda,offline'],
            'waktu_mulai'     => ['nullable', 'date'],
            'waktu_akhir'     => ['nullable', 'date', 'after_or_equal:waktu_mulai'],
            'url'             => ['nullable', 'url', 'max:500', 'required_if:jenis_agenda,online'],
            'featured_image'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'images'          => ['nullable', 'array'],
            'images.*'        => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'attachments'     => ['nullable', 'array', 'max:10'],
            'attachments.*'   => ['file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png,webp', 'max:5120'],
            'remove_attachments'   => ['nullable', 'array'],
            'remove_attachments.*' => ['exists:agenda_attachments,uuid'],
            'remove_images'   => ['nullable', 'array'],
            'remove_images.*' => ['exists:agenda_images,uuid'],
            'tagging'         => ['nullable', 'string', 'max:255'],
            'video'           => ['nullable', 'string'],
            'status'          => ['required', 'in:draft,published,scheduled,pending'],
            'search_engine'   => ['required', 'in:index,noindex'],
            'is_featured'     => ['nullable', 'boolean'],
            'is_popular'      => ['nullable', 'boolean'],
            'is_urgent'       => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'         => 'Judul agenda wajib diisi.',
            'title.max'              => 'Judul agenda maksimal 255 karakter.',
            'title.unique'           => 'Judul agenda sudah digunakan.',
            'content.required'       => 'Isi agenda wajib diisi.',
            'category_uuid.required' => 'Kategori wajib dipilih.',
            'category_uuid.exists'   => 'Kategori yang dipilih tidak valid.',
            'jenis_agenda.required'  => 'Jenis agenda wajib dipilih.',
            'jenis_agenda.in'        => 'Jenis agenda hanya boleh offline atau online.',
            'lokasi.required_if'     => 'Lokasi wajib diisi untuk agenda offline.',
            'waktu_akhir.after_or_equal' => 'Waktu akhir tidak boleh lebih awal dari waktu mulai.',
            'url.url'                => 'URL harus berupa tautan yang valid.',
            'url.required_if'        => 'URL wajib diisi untuk agenda online.',
            'featured_image.image'   => 'Gambar utama harus berupa gambar.',
            'featured_image.max'     => 'Ukuran gambar utama maksimal 2 MB.',
            'images.*.image'         => 'Setiap foto slider harus berupa gambar.',
            'images.*.max'           => 'Ukuran setiap foto slider maksimal 2 MB.',
            'status.required'        => 'Status publikasi wajib dipilih.',
        ];
    }
}
