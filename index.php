<?php 

$error = '';
$email = '';
$class = 'alert-danger';

if(isset($_POST['subscribe']))
{
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email. Please enter a valid email.";
    }

    $curl = curl_init();
    // You can also set the URL you want to communicate with by doing this:
    $apiURL = 'http://newsletter.local/api.php?token=b83f6110ff0612b651587aeeb4269e6fa097282780604fd3f2b5f5f7410e0c5ce4f265ee4e6a2a85d66488c5626e24722aafb774d15c1afa554ef92b0782caec';
    // We POST the data
    curl_setopt($curl, CURLOPT_POST, 1);
    // Set the url path we want to call
    curl_setopt($curl, CURLOPT_URL, $apiURL);  
    // Make it so the data coming back is put into a string
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // Insert the data
    curl_setopt($curl, CURLOPT_POSTFIELDS, ['email' => $email]);

    // Send the request
    $result = curl_exec($curl);

    // Get some cURL session information back
    $info = curl_getinfo($curl);  
    if($info['http_code'] == 200)
    {
        $jsonDecode = json_decode($result);

        if($jsonDecode->code == 0)
        {
            $error = 'Thank you for subscribing our newsletter.';
            $class = 'alert-success';
        }
        else
        {
            $error = $jsonDecode->message;
        }
    }
    else
    {
        $error = 'Unable to connect with the API. Please contact us.';
    }
    // Free up the resources $curl is using
    curl_close($curl);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Newsletter</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>
            .btn-subscribe { margin-left: 10px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-8 offset-sm-2">
                    <h1 class="display-3">Newsletter</h1>
                <div>
                    <?php if(!empty($error)): ?>
                    <div class="alert <?php echo $class ?>">
                        <ul>
                            <li><?php echo $error ?></li>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <form method="post" class="form-inline" action="">
                        <div class="form-group">   
                            <input type="email" class="form-control" name="email" value="<?php echo $email ?>" placeholder="Enter your email address" required/>
                            <button type="submit" name="subscribe" class="btn btn-primary btn-subscribe">Subscribe</button>                            
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>