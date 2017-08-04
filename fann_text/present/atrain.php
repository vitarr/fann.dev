<?php
//==============================Продолжение работы с ранее созданной нейронной сетью================================//

//Воссоздание нейронной сети из файла, в который она была сохранена ранее
$ann = fann_create_from_file("classify.txt");

//Дообучение на новых тренировочных данных
fann_set_learning_rate($ann, 0.7);
fann_set_train_stop_function($ann, FANN_STOPFUNC_MSE);
fann_set_training_algorithm($ann, FANN_TRAIN_BATCH);

$num_input = 256;
$num_output = 4;
$max_epochs = 500000;
$epochs_between_reports = 1000;

$data = [
    [generate_frequencies(file_get_contents("en_new.txt")),[1, 0, 0, 0]],
    [generate_frequencies(file_get_contents("fr_new.txt")),[0, 1, 0, 0]],
    [generate_frequencies(file_get_contents("pl_new.txt")),[0, 0, 1, 0]],
    [generate_frequencies(file_get_contents("ru_new.txt")),[0, 0, 0, 1]],
];

$num_data = sizeof($data);

$train_data = [];
foreach ($data as $value) {
    $data_set = $value;
    $train_data[] = fann_create_train_from_callback($num_data, $num_input, $num_output, function () {
        global $data_set;
        return [$data_set[0],$data_set[1]];
    });
}

$merged_train_data = $train_data[0];
if (sizeof($train_data) >= 2) {
    for ($i = 1; $i < sizeof($train_data); $i++) {
        $merged_train_data = fann_merge_train_data($merged_train_data, $train_data[$i]);
    }
}

$desired_error = fann_get_MSE($ann);

fann_train_on_data($ann, $merged_train_data, $max_epochs, $epochs_between_reports, $desired_error);

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
