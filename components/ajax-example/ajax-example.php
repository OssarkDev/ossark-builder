<?php 
    
    if (isset($args)) {
        $id = $args['id'] ?? '';
    }
?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Ajax HTML Response:</h1>
                <h1><?php echo $id; ?></h1>
            </div>
        </div>
    </div>
</section>