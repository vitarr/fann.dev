<?php

$ann = fann_create_standard(3, 256, 256, 4);
fann_set_learning_rate($ann, 0.7);
fann_set_train_stop_function($ann, FANN_STOPFUNC_MSE);
//fann_set_train_stop_function($ann, FANN_STOPFUNC_BIT);
//fann_set_training_algorithm($ann, FANN_TRAIN_INCREMENTAL);
fann_set_training_algorithm($ann, FANN_TRAIN_BATCH);
fann_set_activation_function_hidden ($ann, FANN_GAUSSIAN);
fann_set_activation_function_output ($ann, FANN_GAUSSIAN);

//------------------------------------------------------------------------------------------------

//$num_data = 1;
//$num_input = 256;
//$num_output = 4;
//
//$data1 = fann_create_train_from_callback($num_data, $num_input, $num_output, function () {
//    return [
//        generate_frequencies(file_get_contents("en.txt")),
//        [1, 0, 0, 0],
//    ];
//});
//$data2 = fann_create_train_from_callback($num_data, $num_input, $num_output, function () {
//    return [
//        generate_frequencies(file_get_contents("fr.txt")),
//        [0, 1, 0, 0],
//    ];
//});
//$data3 = fann_create_train_from_callback($num_data, $num_input, $num_output, function () {
//    return [
//        generate_frequencies(file_get_contents("pl.txt")),
//        [0, 0, 1, 0],
//    ];
//});
//$data6 = fann_create_train_from_callback($num_data, $num_input, $num_output, function () {
//    return [
//        generate_frequencies(file_get_contents("ru.txt")),
//        [0, 0, 0, 1],
//    ];
//});
//
//$data4 = fann_merge_train_data($data1, $data2);
//$data5 = fann_merge_train_data($data3, $data6);
//$train_data = fann_merge_train_data($data4, $data5);

//------------------------------------------------------------------------------------------------

$num_data = 4;
$num_input = 256;
$num_output = 4;

$data = [
    [
        generate_frequencies(file_get_contents("en.txt")),
        [1, 0, 0, 0],
    ],
    [
        generate_frequencies(file_get_contents("fr.txt")),
        [0, 1, 0, 0],
    ],
    [
        generate_frequencies(file_get_contents("pl.txt")),
        [0, 0, 1, 0],
    ],
    [
        generate_frequencies(file_get_contents("ru.txt")),
        [0, 0, 0, 1],
    ],
];

$train_data = [];
foreach ($data as $value) {
    $data_set = $value;
    $train_data[] = fann_create_train_from_callback($num_data, $num_input, $num_output, function () {
        global $data_set;
        return [
            $data_set[0],
            $data_set[1],
        ];
    });
}

$merged_train_data = $train_data[0];
for($i = 1; $i < sizeof($train_data); $i++){
$merged_train_data = fann_merge_train_data($merged_train_data, $train_data[$i]);
}


//-------------------------------------------------------------------------------------------------

//for($i = 0; $i < 100000; $i++)
//{
//    fann_train($ann, generate_frequencies(file_get_contents("en.txt")), [1, 0, 0, 0]);
//    fann_train($ann, generate_frequencies(file_get_contents("fr.txt")), [0, 1, 0, 0]);
//    fann_train($ann, generate_frequencies(file_get_contents("pl.txt")), [0, 0, 1, 0]);
//    fann_train($ann, generate_frequencies(file_get_contents("ru.txt")), [0, 0, 0, 1]);
//
//}

fann_train_on_data($ann, $merged_train_data, 500000, 50, fann_get_MSE($ann));
//fann_train_on_data($ann, $data, 100000, 1000, fann_get_bit_fail($ann));

fann_save($ann, "classify.txt");

function generate_frequencies($text)
{

    $text = preg_replace("/[^\p{L}]/iu", "", strtolower($text));

    $total = strlen($text);
    $data = count_chars($text);

    array_walk($data, function (&$item, $key, $total) {
        $item = round($item / $total, 3);
    }, $total);

    return array_values($data);
}
