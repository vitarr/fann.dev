<?php
//============================Использование ранее созданной нейронной сети==============================//
$languages = [
    'English',
    'French',
    'Polish',
    'Russian',
];

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

$ann = fann_create_from_file("classify.txt");

$output = fann_run($ann, generate_frequencies("ANN are slowly adjusted so as to produce the same output as in
            the examples. The hope is that when the ANN is shown a new
            X-ray images containing healthy tissues"));

$answer = array_combine($languages, $output);
var_dump($answer);

$output = fann_run($ann, generate_frequencies("Voyons, Monsieur, absolument pas,
 les camions d’aujourd’hui ne se traînent pas, bien au contraire. Il leur arrive même de pousser les voitures.
  Non, croyez moi, ce qu’il vous faut, c’est un camion !
     - Vous croyez ? Si vous le dites. Est-ce que je pourrais l’avoir en rouge ?
     - Bien entendu cher Monsieur,vos désirs sont des ordres, vous l’aurez dans quinze jours clé en main. 
     Et la maison sera heureuse de vous offrir le porte-clé. Si vous payez comptant. Cela va sans dire,
      ajouta Monsieur Filou.
     - Ah, si ce "));

$answer = array_combine($languages, $output);
var_dump($answer);

$output = fann_run($ann, generate_frequencies("tworząc dzieło literackie, pracuje na języku.
 To właśnie język stanowi tworzywo, dzięki któremu powstaje tekst. Język literacki
  ( lub inaczej artystyczny) powstaje poprzez wybór odpowiednich środków i przy wykorzystaniu odpowiednich 
  zabiegów technicznych.
            Kompozycja - jest to układ elementów treściowych i formalnych dzieła dokonanych według 
            określonych zasad konstrukcyjnych.
            Kształtowanie tworzywa dzieła literackiego jest procesem skomplikowanym i przebiegającym na 
            wielu poziomach.
            Składa się na nie:"));

$answer = array_combine($languages, $output);
var_dump($answer);

$output = fann_run($ann, generate_frequencies("Есть много вариантов Lorem Ipsum, но большинство из них 
имеет не всегда приемлемые модификации, например, юмористические вставки или слова, которые даже отдалённо не 
напоминают латынь. Если вам нужен Lorem Ipsum для серьёзного проекта, вы наверняка не хотите какой-нибудь шутки, 
скрытой в середине абзаца. Также все другие известные генераторы Lorem Ipsum используют один и тот же текст, который 
они просто повторяют, пока не достигнут нужный объём. Это делает предлагаемый здесь генератор единственным настоящим 
Lorem Ipsum генератором. Он использует словарь из более чем 200 латинских слов, а также набор моделей предложений.
 В результате сгенерированный Lorem Ipsum выглядит правдоподобно, не имеет повторяющихся абзацей или \"невозможных\" слов."));

$answer = array_combine($languages, $output);
var_dump($answer);
