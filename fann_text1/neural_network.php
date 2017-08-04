<?php

/*
 * Задаем параметры сети. 256 - это количество входов, оно должно равняться количеству ваших параметров,
 * По хорошему в этом примере должно быть намного меньне, равно количесству букв в алфавитах.
 * 128 - это количество нейронов в промежуточном слое. Здесь нуужно экспериментальным путем подбирать это число.
 * 3 это количество выходящих сигналов. По скольку у нас 3 языка, то для каждого будет возвращена вероятность
 * 1.0 - connection_rate - его лучше не менять
 * 0.7 - learning_rate - описано здесь хорошо http://www.basegroup.ru/glossary/definitions/learning_rate/
 * */
$ann = fann_create_standard(3, 256, 128, 3);
fann_set_learning_rate($ann, 0.7);

/*
 * Первый параметр - указатель на нашу сеть, второй - обучающие данные.
 * Мы загружаем 3 порции данных.Каждая порция состоит их входящих показателей и эталонных результирующих.
 * В нем мы сообщаем, что при таких показателях, как мы сейчас передаем, нужно весь вес
 * отдавать на первы нейрон (array(1, 0, 0) // Outputs). при загрузке других типов данный мы смещаем вес на другой нейрон
 * generate_frequencies - просто расчитывает частоты.
 *
 * Последние 3 параметра это
 * - максимальное кол-во итераций
 * - максимальное кол-во ошибок
 * - промежуток между выводами информации
 *
 * В файлах en.txt, fr.txt, pl.txt хранится текс размером где-то в 10000 символов для конкретного языка
 * */

$num_data = 1;
$num_input = 256;
$num_output = 3;

//var_dump(sizeof(generate_frequencies(file_get_contents("en.txt"))));
//var_dump(sizeof(generate_frequencies(file_get_contents("fr.txt"))));
//var_dump(sizeof(generate_frequencies(file_get_contents("pl.txt"))));die();

$data1 = fann_create_train_from_callback($num_data, $num_input, $num_output, function () {
    return [
        generate_frequencies(file_get_contents("en.txt")), // Inputs
        [1, 0, 0], // Outputs
    ];
});
$data2 = fann_create_train_from_callback($num_data, $num_input, $num_output, function () {
    return [
        generate_frequencies(file_get_contents("fr.txt")), // Inputs
        [0, 1, 0], // Outputs
    ];
});
$data3 = fann_create_train_from_callback($num_data, $num_input, $num_output, function () {
    return [
        generate_frequencies(file_get_contents("pl.txt")), // Inputs
        [0, 0, 1], // Outputs
    ];
});

$data4 = fann_merge_train_data($data1, $data2);
$data = fann_merge_train_data($data4, $data3);

fann_train_on_data($ann, $data, 5000000, 100, fann_set_train_stop_function($ann, FANN_TRAIN_SARPROP));

/*
 * Сохранить нашу модель в файл. в дальнейшем ее можно использовать для класификации
 * */
fann_save($ann, "classify.txt");

/*
 * Функция расчета частот
 * */
function generate_frequencies($text)
{
    // Удалим все кроме букв
    $text = preg_replace("/[^\p{L}]/iu", "", strtolower($text));

    // Найдем параметры для расчета частоты
    $total = strlen($text);
    $data = count_chars($text);

    // Ну и сам расчет
    array_walk($data, function (&$item, $key, $total) {
        $item = round($item / $total, 3);
    }, $total);

    return array_values($data);
}

//

/*
 * Загружаем модель из файла. Эту модель мы создали на предыдущем шаге.
 * */
$ann = fann_create_from_file("classify.txt");

/*
 * Ниже я в нашу сеть передаю 3 текста на разных языках
 * Смотрим результат
 * */

$output = fann_run($ann, generate_frequencies("ANN are slowly adjusted so as to produce the same output as in
            the examples. The hope is that when the ANN is shown a new
            X-ray images containing healthy tissues"));

var_dump($output);

$output = fann_run($ann, generate_frequencies("Voyons, Monsieur, absolument pas, les camions d’aujourd’hui ne se traînent pas, bien au contraire. Il leur arrive même de pousser les voitures. Non, croyez moi, ce qu’il vous faut, c’est un camion !
     - Vous croyez ? Si vous le dites. Est-ce que je pourrais l’avoir en rouge ?
     - Bien entendu cher Monsieur,vos désirs sont des ordres, vous l’aurez dans quinze jours clé en main. Et la maison sera heureuse de vous offrir le porte-clé. Si vous payez comptant. Cela va sans dire, ajouta Monsieur Filou.
     - Ah, si ce "));

var_dump($output);

$output = fann_run($ann, generate_frequencies("tworząc dzieło literackie, pracuje na języku. To właśnie język stanowi tworzywo, dzięki któremu powstaje tekst. Język literacki ( lub inaczej artystyczny) powstaje poprzez wybór odpowiednich środków i przy wykorzystaniu odpowiednich zabiegów technicznych.
            Kompozycja - jest to układ elementów treściowych i formalnych dzieła dokonanych według określonych zasad konstrukcyjnych.
            Kształtowanie tworzywa dzieła literackiego jest procesem skomplikowanym i przebiegającym na wielu poziomach.
            Składa się na nie:"));

var_dump($output);
