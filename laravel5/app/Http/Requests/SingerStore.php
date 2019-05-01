<?php

namespace App\Http\Requests;

use App\Singer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SingerStore extends FormRequest
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
            'name'      => 'required|min:1|max:150',
            'slug'      => 'required|min:1|max:100|unique:singers,slug,'.$this->route('singer'),
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

        if (!$this->has('slug') && $this->has('name')) {
            $this->merge(['slug' => $this->createSlug($this->get('name'))]);
        }
    }

    /**
     * Creates a slug after controls there is or not
     *
     * @param $name
     * @param int $c
     *
     * @return string
     */
    protected function createSlug($name, $c = 0)
    {
        $slug   = Str::slug($name);

        if ($c > 0) {
            $slug   = $slug.'-'.$c;
        }

        if ($slug != '') {
            $slug_query = Singer::where(['slug' => $slug]);

            if ($slug_query->count() > 0) {
                return $this->createSlug($name, $c+1);
            }
        }

        return $slug;
    }
}
