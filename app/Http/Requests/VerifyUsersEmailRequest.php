<?php

namespace App\Http\Requests;

use App\Models\Member;
use App\Models\Volunteer;
use App\Models\Subscriber;
use Illuminate\Foundation\Http\FormRequest;


class VerifyUsersEmailRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! hash_equals((string) $this->route('id'),
                          (string) $this->user()->id)) {
            return false;
        }

        if (! hash_equals((string) $this->route('hash'),
                          sha1($this->user()->email))) {
            return false;
        }

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
            //
        ];
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill()
    {
        if (! $this->user()->hasVerifiedEmail()) {
            $this->user()->markEmailAsVerified();

            event(new Verified($this->user()));
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        return $validator;
    }
    
    public function user($guard = null)
    {
      $id   = $this->route('id');
      $type = $this->route('type');
      switch($type) {
        case 'volunteer':
          return Volunteer::find($id);
          break;
        case 'member':
          return Member::find($id);
          break;
        case 'subscriber':
          return Subscriber::find($id);
          break;

      }
    }
}
