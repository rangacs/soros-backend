<?php

class kalmanFilter
{
    public $K = 0;
    public $P = 0;
    public $Q = 0.001;
    public $R = 2.5;

    function doFilter( $z_measured, $numSamples = 0 )
    {
        $x_est = array();
        $x_est_last = $z_measured[0];

        // use previous value of P each time doFilter is called
        $P_last =$this->P;
     
        for ( $i = 0; $i < $numSamples; $i++ )
        {
            // calculate the Kalman gain 
            $this->K = ($P_last + $this->Q) * (1.0/( ($P_last + $this->Q) + $this->R));

            // calculate the kalman estimated value 
            $x_est[$i] = $x_est_last + $this->K * ($z_measured[$i] - $x_est_last);  

            // calcucate the covariance
            $this->P = (1- $this->K) * ( $P_last + $this->Q ); 

            // update recursive variables 
            $P_last = $this->P; 
            $x_est_last = $x_est[$i];

        }

        return $x_est;
    }
}

if( 0 )
{
    $numSamples = 200;
    $z_measured = array();
    $x_estimated = array();

    $kf = new kalmanFilter();

    for ( $i = 0; $i < $numSamples; $i++ )
    {
        $z_measured[$i] = 0.5 + rand(-10,10)*0.009; 
    }

    $kf->Q = 0.0005;
    $kf->R = 2.5;
    $x_estimated = $kf->doFilter( $z_measured, $numSamples );

    for ( $i = 0; $i < $numSamples; $i++ )
    {
        //we have our new system 
        echo "Measured value: $z_measured[$i]\n"; 
        echo "Kalman   value: $x_estimated[$i]\n";
    }
    echo "Kalman   K: " . $kf->K . "\n"; 
    echo "Kalman   P: " . $kf->P . "\n"; 
    echo "Kalman   Q: " . $kf->Q . "\n"; 
    echo "Kalman   R: " . $kf->R . "\n"; 
}

?>
