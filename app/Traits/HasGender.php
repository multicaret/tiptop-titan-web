<?php


namespace App\Traits;

use App\Models\User;

trait HasGender {


    public static function determineGender($gender)
    {
        if ($gender == null) {
            return null;
        }
        switch (strtolower($gender)) {
            case 'f':
            case 'female':
            case 'أنثى':
            case self::GENDER_FEMALE:
                return self::GENDER_FEMALE;
            case 'm':
            case 'male':
            case 'ذكر':
            case self::GENDER_MALE:
                return self::GENDER_MALE;
            default:
                return self::GENDER_UNSPECIFIED;
        }
    }

    public static function getGender($gender)
    {
        switch ($gender) {
            case User::GENDER_MALE:
                return trans('strings.sex_male');
            case User::GENDER_FEMALE:
                return trans('strings.sex_female');
            default:
                return trans('strings.sex_other');
        }
    }
}
