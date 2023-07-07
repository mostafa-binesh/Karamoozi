<?php
namespace App\Http\Controllers;
class NumberToWords
{
    protected $words;
    protected $suffixes;

    public function __construct($words)
    {
        $this->words = $words;
        $this->suffixes = [
            3 => 'هزار',
            6 => 'میلیون',
            9 => 'میلیارد',
            12 => 'تریلیون'
            // add more suffixes as needed
        ];
    }

    public function convert($number)
    {
        if (!is_numeric($number)) {
            return false;
        }

        $num_words = array();

        // Split the number into integer and decimal parts
        $parts = explode('.', $number);

        // Handle the integer part
        $integer_part = $parts[0];
        if ($integer_part < 0) {
            $num_words[] = 'منفی';
            $integer_part = abs($integer_part);
        }

        // Convert the integer part to words
        $suffix = '';
        foreach ($this->suffixes as $digits => $suffix_value) {
            if ($integer_part >= pow(10, $digits)) {
                $suffix = $suffix_value;
            } else {
                break;
            }
        }

        while ($integer_part > 0) {
            if ($integer_part < 21) {
                $num_words[] = $this->words[$integer_part];
                $integer_part = 0;
            } elseif ($integer_part < 100) {
                $num_words[] = $this->words[10 * floor($integer_part / 10)];
                $integer_part %= 10;
            } else {
                $num_words[] = $this->words[floor($integer_part / 100)] . ' صد';
                $integer_part %= 100;
            }
        }

        if (!empty($suffix)) {
            $num_words[] = $suffix;
        }

        // Handle the decimal part
        if (count($parts) > 1 && is_numeric($parts[1])) {
            $decimal_part = $parts[1];
            $num_words[] = 'ممیز';
            for ($i = 0; $i < strlen($decimal_part); $i++) {
                if ($decimal_part[$i] < 21) {
                    $num_words[] = $this->words[$decimal_part[$i]];
                } elseif ($decimal_part[$i] < 100) {
                    $num_words[] = $this->words[10 * floor($decimal_part[$i] / 10)];
                    if ($decimal_part[$i] % 10 > 0) {
                        $num_words[] = $this->words[$decimal_part[$i] % 10];
                    }
                }
            }
        }

        // Add separators
        $num_words = array_reverse($num_words);
        $num_words_with_separators = array();
        for ($i = 0; $i < count($num_words); $i++) {
            if ($i > 0 && $i % 3 == 0) {
                $num_words_with_separators[] = 'و';
            }
            $num_words_with_separators[] = $num_words[$i];
        }

        // Return the final result
        return implode(' ', array_reverse($num_words_with_separators));
    }
}
