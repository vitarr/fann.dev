<?php

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

//$output = fann_run($ann, generate_frequencies("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
//
//"));
//
//var_dump($output);
//
//$output = fann_run($ann, generate_frequencies("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un peintre anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.
//
//"));
//
//var_dump($output);
//
//$output = fann_run($ann, generate_frequencies("Lorem Ipsum jest tekstem stosowanym jako przykładowy wypełniacz w przemyśle poligraficznym. Został po raz pierwszy użyty w XV w. przez nieznanego drukarza do wypełnienia tekstem próbnej książki. Pięć wieków później zaczął być używany przemyśle elektronicznym, pozostając praktycznie niezmienionym. Spopularyzował się w latach 60. XX w. wraz z publikacją arkuszy Letrasetu, zawierających fragmenty Lorem Ipsum, a ostatnio z zawierającym różne wersje Lorem Ipsum oprogramowaniem przeznaczonym do realizacji druków na komputerach osobistych, jak Aldus PageMaker"));
//
//var_dump($output);

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

$output = fann_run($ann, generate_frequencies("Есть много вариантов Lorem Ipsum, но большинство из них имеет не всегда приемлемые модификации, например, юмористические вставки или слова, которые даже отдалённо не напоминают латынь. Если вам нужен Lorem Ipsum для серьёзного проекта, вы наверняка не хотите какой-нибудь шутки, скрытой в середине абзаца. Также все другие известные генераторы Lorem Ipsum используют один и тот же текст, который они просто повторяют, пока не достигнут нужный объём. Это делает предлагаемый здесь генератор единственным настоящим Lorem Ipsum генератором. Он использует словарь из более чем 200 латинских слов, а также набор моделей предложений. В результате сгенерированный Lorem Ipsum выглядит правдоподобно, не имеет повторяющихся абзацей или \"невозможных\" слов."));

var_dump($output);
