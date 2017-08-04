<?php
$word =  crc32('Школа');
$word_code = '0.'.$word;
var_dump($word);
var_dump((double)$word_code);


$text = 'Надання загальної середньої освіти загальноосвітніми навчальними закладами (в т.ч. школою – дитячим садком, інтернатом при школі), спеціалізованими школами, ліцеями, гімназіями, колегіумами';

// Get an array of all the words

$allWordsArray = preg_split('/[\s,().]+/', $text, 0, PREG_SPLIT_NO_EMPTY);

// returns only words that have minimum 2 chars
$allWordsArray = array_filter($allWordsArray, function($val) {
    return strlen($val) >= 4;
});

$totalAllWordsArray = count($allWordsArray);

// Get the amount of times a word appears on the page
$wordCount = array_count_values($allWordsArray);
arsort($wordCount);

// Get the top 20 words
$wordCount = array_splice($wordCount, 0);

// Loop through all the word count array and work out the percentage of a word appearing on the page
$percentageCount = [];
$words_code = [];
foreach($wordCount as $words => $val)
{
    $percentageCount[$words] = number_format(($val / $totalAllWordsArray) * 100, 2);
    $words_code[$words] =  (double)('0.'.(crc32($words)));
}

//var_dump($percentageCount);
//var_dump($words_code);
//
//$str = bin2hex('школа');
//var_dump($str);
//$str = base_convert($str, 16, 10);
//var_dump($str);
//
//$str = bin2hex('школьник');
//var_dump($str);
//$str = base_convert($str, 16, 10);
//var_dump($str);
$word = 'школа';
$word_code = '';
$word = str_split($word);

foreach ($word as $char){
    $word_code .= ord($char);
}

echo  '0.'.$word_code;

$word = 'школи';
$word_code = '';
$word = str_split($word);

foreach ($word as $char){
    $word_code .= ord($char);
}

echo '<br>';

echo  '0.'.$word_code;

$word = 'школами';
$word_code = '';
$word = str_split($word);

foreach ($word as $char){
    $word_code .= ord($char);
}

echo '<br>';

echo  '0.'.$word_code;
