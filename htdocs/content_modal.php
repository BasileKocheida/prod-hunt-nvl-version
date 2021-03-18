<?php 
session_start();
if (isset($_POST['product_id'])) :
    require_once("connexion/bdd.php");
    $productStatement =  $pdo->prepare('SELECT * FROM product WHERE id = ?');
    $productStatement->execute([$_POST['product_id']]);
    $product = $productStatement->fetch();
    $idproduct= $product['id'];
    //commentaires 
    
    
    $commentaireStatement = $pdo->prepare('SELECT * FROM commentaires 
    JOIN user ON commentaires.user_id = user.id WHERE product_id = ?');
    $commentaireStatement->execute([$_POST['product_id']]);
    $result = $commentaireStatement->fetchAll();
    ?>

    <div class="popup-header">
        <div class="card mb-3 modal-content" >
            <div class="row no-gutters">
                <div class="col-md-2">
                    <img src="<?=$product['logo']?>" class="card-img" alt="...">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">"<?=$product['name-product']?>"</h5>
                        <p class="card-text"><?=$product['descriptif']?> </p>
                        <p class="card-text"><?=$product['categories']?> </p>
                    </div>
                </div>
                <div class="col-md-2">
                </div>
            </div> 
         </div>  
    </div>

    <div class="popup-body">

        <?php 
        $getImage = $pdo->prepare('SELECT * FROM images WHERE product_id = ?');
        $getImage->execute([$idproduct]);
        $img = $getImage->fetch(); ?>
               
               <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                            <img src="<?=$img["img_1"]?>" class="d-block w-100">
                            </div>
                            <div class="carousel-item">
                            <img src="<?=$img["img_2"]?>" class="d-block w-100">
                            </div>
                            <div class="carousel-item">
                            <img src="<?=$img["img_3"]?>" class="d-block w-100">
                            </div>
                        </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"  data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"  data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
            </div>
    </div>
            <div class="popup-footer" id='refresh'>
            <h5>Espace commentaires</h5><br>
                <?php 

                $dateCommentaireStatement = $pdo->prepare('SELECT * FROM commentaires');
                $dateCommentaireStatement->execute();
                $date = $dateCommentaireStatement->fetch();

                $dateParts= explode("-", $date["created_at"]);
                $dateFormatted = $dateParts[2]. "/".$dateParts[1]. "/" .$dateParts[0];

                foreach($result as $commentaire) :?>
                    <div class="commentaires" >
                        <p class="nickname"><b> <?=$commentaire['nickname']?> </b> Post : <?= $dateFormatted ?></p>
                        <p class="commentaire"> <?=$commentaire['text_commentaire']?></p>
                        
                    </div>
                <?php  endforeach;?>


                
                    <div class='nblike' id='<?=$product['id']?>'>
                        <p>Nombre de likes : </p> <?php include 'recuperation-donnees/count_up.php' ?>
                    </div>
                


                <input id= "product_id" value="<?=$_POST['product_id']?>" type="hidden">
                <input id= "user_id" value="<?=$_SESSION['id']?>" type="hidden">
                <textarea name="commentaires" id="commentaire" placeholder="votre commentaire..."> </textarea>
                <button type="submit" class="btn btn-primary" id='envoyer' onclick="send();">Envoyer</button>
                
            </div>
        </div>
    </div>
<?php endif;?>