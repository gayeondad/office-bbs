<?php
// filepath: d:\myrefer\PHP\cls\configuration\Validator.cls.php
namespace cls\helper;

class Validator {

    /**
     * 정수 값 검증
     *
     * @param mixed $value 검증할 값
     * @param int|null $min 최소값 (null 허용)
     * @param int|null $max 최대값 (null 허용)
     * @return int|null 검증된 정수 값, 실패 시 null 반환
     */
    public static function validateInt($value, ?int $min = null, ?int $max = null): ?int {
        $value = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min, 'max_range' => $max]]);
        return $value === false ? null : $value;
    }

    /**
     * 문자열 값 검증
     *
     * @param mixed $value 검증할 값
     * @param int|null $minLength 최소 길이 (null 허용)
     * @param int|null $maxLength 최대 길이 (null 허용)
     * @return string|null 검증된 문자열 값, 실패 시 null 반환
     */
    public static function validateString($value, ?int $minLength = null, ?int $maxLength = null): ?string {
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        if (!is_string($value)) {
            return null;
        }

        $length = strlen($value);
        if ($minLength !== null && $length < $minLength) {
            return null;
        }
        if ($maxLength !== null && $length > $maxLength) {
            return null;
        }

        return $value;
    }

    /**
     * 이메일 주소 검증
     *
     * @param mixed $value 검증할 값
     * @return string|null 검증된 이메일 주소, 실패 시 null 반환
     */
    public static function validateEmail($value): ?string {
        $value = filter_var($value, FILTER_VALIDATE_EMAIL);
        return $value === false ? null : $value;
    }

    /**
     * 날짜 형식 검증 (YYYY-MM-DD)
     *
     * @param mixed $value 검증할 값
     * @return string|null 검증된 날짜 문자열, 실패 시 null 반환
     */
    public static function validateDate($value): ?string {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }

        $parts = explode('-', $value);
        if (count($parts) !== 3) {
            return false; // 분리된 부분이 3개가 아니면 형식 오류
        }
        if (!checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0])) {
            return null;
        }

        return $value;
    }

    /**
     * URL 검증
     *
     * @param mixed $value 검증할 값
     * @return string|null 검증된 URL, 실패 시 null 반환
     */
    public static function validateUrl($value): ?string {
        $value = filter_var($value, FILTER_VALIDATE_URL);
        return $value === false ? null : $value;
    }
}
