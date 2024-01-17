--TEST--
Test function fann_train_epoch_irpropm_parallel() by calling it with its expected arguments
--FILE--
<?php
$num_threads = 4;

$num_input = 2;
$num_output = 1;
$num_layers = 3;
$num_neurons_hidden = 3;
$desired_error = 0.001;
$max_epochs = 50000;
$epochs_between_reports = 1000;

$ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);

$filename = ( dirname( __FILE__ ) . "/fann_train_epoch.tmp" );				 
$content = <<<EOF
4 2 1
-1 -1
-1
-1 1
1
1 -1
1
1 1
-1
EOF;

file_put_contents( $filename, $content );
$train_data = fann_read_train_from_file( $filename );

$error = 0;
for($i = 1; $i <= $max_epochs; $i++)
{
	$error = fann_train_epoch_irpropm_parallel($ann, $train_data, $num_threads);
	printf("Epochs     %8d. Current error: %.10f\n", $i, $error);
	if($error <= $desired_error){
		print("done\n");
		break;
	}
}
var_dump($error);

?>
--CLEAN--
<?php
$filename = ( dirname( __FILE__ ) . "/fann_train_epoch.tmp" );
if ( file_exists( $filename ) )
	unlink( $filename );
?>
--EXPECT--

