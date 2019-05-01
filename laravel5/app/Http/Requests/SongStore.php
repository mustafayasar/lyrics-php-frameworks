<?php

namespace App\Http\Requests;

use App\Singer;
use App\Song;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SongStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'singer_id' => 'required|integer|exists:singers,id',
            'title'     => 'required|min:1|max:255',
            'slug'      => 'required|min:1|max:150',
            'lyrics'    => 'required',
            'status'    => ['required', Rule::in(array_keys(Singer::$statuses))]
        ];
    }

    /**
     * prepareForValidation
     */
    protected function prepareForValidation()
    {
        if ($this->isMethod('post'))
        {
            if (!$this->has('status')) {
                $this->merge(['status' => Singer::STATUS_ACTIVE]);
            }
        }

        if (!$this->has('slug') && $this->has('title')) {
            $this->merge(['slug' => $this->createSlug($this->get('title'), $this->get('singer_id'), $this->route('song', 0))]);
        } else {
            $this->merge(['slug' => $this->createSlug($this->get('slug'), $this->get('singer_id'), $this->route('song', 0))]);
        }

        $this->merge(['lyrics' => nl2br($this->get('lyrics'))]);
    }

    /**
     * Creates a slug after controls there is or not
     *
     * @param $title
     * @param $singer_id
     * @param int $id
     * @param int $c
     *
     * @return string
     */
    protected function createSlug($title, $singer_id, $id = 0, $c = 0)
    {
        $slug   = Str::slug($title);

        if ($c > 0) {
            $slug   = $slug.'-'.$c;
        }

        if ($slug != '') {
            $slug_query = Song::where(['slug' => $slug, 'singer_id' => $singer_id]);

            if ($id > 0) {
                $slug_query->where('id', '!=', $id);
            }

            if ($slug_query->count() > 0) {
                return $this->createSlug($title, $singer_id, $id,$c+1);
            }
        }

        return $slug;
    }
}
