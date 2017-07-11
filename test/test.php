<?php
$ann = fann_create_standard_array(3, array(2, 1, 2));
var_dump(fann_get_num_input($ann));
var_dump(fann_get_num_output($ann));
fann_randomize_weights($ann,1,100);
var_dump(fann_get_total_neurons($ann));


for($i = 0; $i < 500000; $i++)
{
    if ($i % 100000 == 0);
    fann_train($ann, array(25, 13), array(-1, 1));
}
var_dump(fann_test($ann, array(25, 13), array(-1, 1)));

var_dump(fann_run($ann,array(25,13)));
var_dump(fann_get_connection_array($ann));
