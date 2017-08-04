<?php
//==============================Нейронная сеть с PHP FANN=======================================//

//Количество слоев нейронов
$num_layers = 3;

//Количество входных нейронов
$num_input = 256;

//Количество нейронов в единственном скрытом слое
$num_neurons_hidden = 128;

//Количество выходных нейронов
$num_output = 4;

//Количество тренировочных эпох
$max_epochs = 500000;

//Интервал в эпохах между вызовами пользовательской функции
$epochs_between_reports = 1000;

//Инициализация нейронной сети
$ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);

//Установка интенсивности обучения
fann_set_learning_rate($ann, 0.7);

//Критерий остановки - значение среднеквадраатичной ошибки (Mean Square Error или MSE).
fann_set_train_stop_function($ann, FANN_STOPFUNC_MSE);

//Стандартный алгоритм обратного распространения ошибки,
//в котором веса обновляются после вычисления среднеквадратичная погрешность на всем обучающем наборе.
//Это обозначает, что веса обновляются всего один раз в течении одной эпохи.
//Это приводит к тому, что для некоторых задач обучение будет происходить медленнее.
//С другой стороны, вычисление среднеквадратичная погрешности более корректно,
//нежели чем в инкрементальном обучении, что позволяет получить более качественную сеть.
fann_set_training_algorithm($ann, FANN_TRAIN_BATCH);

//Массив сетов тренировочных данных
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

//Количество сетов тренировочных данных
$num_data = sizeof($data);

//Слияние все сетов тренировочных данных в один
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
for ($i = 1; $i < sizeof($train_data); $i++) {
    $merged_train_data = fann_merge_train_data($merged_train_data, $train_data[$i]);
}

//Желаемая fann_get_MSE() или fann_get_bit_fail(),
//в зависимости от функции останова выбранной fann_set_train_stop_function().
//В данном случае - MSE.
$desired_error = fann_get_MSE($ann);

//Тренировка сети
fann_train_on_data($ann, $merged_train_data, $max_epochs, $epochs_between_reports, $desired_error);

//Сохранение тренированной сети в файл
fann_save($ann, "classify.txt");

//Функция расчета частоты вхождения символа в документ
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
