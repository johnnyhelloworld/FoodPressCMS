<form method="<?= $config["config"]["method"]??"POST" ?>"
    action="<?= $config["config"]["action"]??""?>"
    id="<?= $config["config"]["id"]??""?>"
    class="<?= $config["config"]["class"]??""?>">

    <?php foreach ($config["inputs"] as $name=>$input) :
        switch ($input["type"]):
            case "radio":
                foreach($input['value'] as $key=>$value):?>
                <input name="<?= $name ?>" 
                    type="radio" 
                    id="<?= $input["id"]??"" ?>" 
                    class="<?= $input["class"]??"" ?>" 
                    value="<?= $value??""?>"
                    <?php if(!empty( $input["checked"])) echo ($value === $input['checked'])? 'checked': '';?>>
                <label><?= $key??"Choix"?></label>
                <br>
                <?php 
                endforeach;
                break;
                ?>
        <?php case 'email':
                ?>
                <div class="logo__text">
                    <img class="logo" src="../../public/assets/images/mail.png" alt="email">
                    <label><?= $input["label"] ?></label>
                </div>
                <input name="<?= $name ?>" class="<?= $input["class"] ?? "" ?>" id="<?= $input["id"] ?? "" ?>" placeholder="<?= $input["placeholder"] ?? "" ?>" type="<?= $input["type"] ?? "text" ?>" value="<?= $input["value"] ?? "" ?>" <?= !empty($input["required"]) ? 'required="required"' : ""  ?>><br>
                <?php break; 
                ?>
        <?php case 'password': 
                ?>
                <div class="logo__text">
                <img class="logo" src="../../public/assets/images/lock.png" alt="password">
                <label><?= $input["label"] ?></label>
                </div>
                <input name="<?= $name ?>" class="<?= $input["class"] ?? "" ?>" id="<?= $input["id"] ?? "" ?>" placeholder="<?= $input["placeholder"] ?? "" ?>" type="<?= $input["type"] ?? "text" ?>" value="<?= $input["value"] ?? "" ?>" <?= !empty($input["required"]) ? 'required="required"' : ""  ?>><br>
                <?php break; 
                ?>
        <?php case "checkbox":
                foreach($input['value'] as $key=>$value):?>
                    <input name="<?= $name ?>" 
                        type="checkbox" 
                        id="<?= $input["id"]??"" ?>" 
                        class="<?= $input["class"]??"" ?>" 
                        value="<?= $value??""?>"
                        <?php if(!empty( $input["checked"])) echo ($value === $input['checked'])? 'checked': '';?>>
                    <label><?= $key??"Choix"?></label>
                    <br>
                <?php 
                endforeach;
                break;
                ?>
        <?php case "select":?>
                <select name="<?= $name ?>" id="<?= $input["id"] ?? "" ?>" class="<?= $input["class"] ?? "" ?>">
                    <option value="">Veuillez choisir</option>
                    <?php foreach ($input['value'] as $value) : ?>
                        <?php if ($input["selectedValue"] == $value[0]) : ?>
                            <option value="<?= $value[0] ?>" selected><?= $value[1]  ?></option>
                        <?php else : ?>
                            <option value="<?= $value[0] ?>"><?= $value[1]  ?></option>
                        <?php endif ?>
                    <?php endforeach; ?>
                </select>
                <br>
                <?php break;?>
        <?php case "text":?>
                <?php if($_SERVER['REQUEST_URI'] == '/register') :?>
                    <div class="logo__text">
                        <img class="logo" src="../../public/assets/images/user.png" alt="logo">
                        <label><?= $input["label"] ?></label>
                    </div>
                    <input name="<?= $name ?>" class="<?= $input["class"] ?? "" ?>" id="<?= $input["id"] ?? "" ?>" placeholder="<?= $input["placeholder"] ?? "" ?>" type="<?= $input["type"] ?? "text" ?>" value="<?= $input["value"] ?? "" ?>" <?= !empty($input["required"]) ? 'required="required"' : ""  ?>><br>
                <?php endif?>
                <?php break;?>
        <?php case "textarea":?>
                <textarea name="<?= $name ?>" class="<?= $input["class"]??"" ?>" id="<?= $input["id"]??"" ?>" placeholder="<?= $input["placeholder"]??"" ?>"><?= $input["value"] ?? '' ?></textarea>
                <br>
                <?php break;?>
        <?php case "file":?>
            <input 
                type="file" 
                name="<?= $name ?>" 
                class="<?= $input["class"]??"" ?>" 
                id="<?= $input["id"]??"" ?>"
                <?= !empty( $input["multiple"])?'multiple':""  ?>>
            <br>
            <?php break;?>
        <?php default:?>
                <input name="<?= $name ?>"
                class="<?= $input["class"]??"" ?>"
                id="<?= $input["id"]??"" ?>"
                placeholder="<?= $input["placeholder"]??"" ?>"
                type="<?= $input["type"]??"text" ?>"
                value="<?= $input["value"] ?? "" ?>" 
                <?= !empty( $input["required"])?'required="required"':""  ?>
                ><br>                
        <?php endswitch;?>        
    <?php endforeach;?>

    <input type="submit" value="<?= $config["config"]["submit"]??"Envoyer"?>">
    
    <?php if($_SERVER['REQUEST_URI'] == '/register') :?>
    <p class="link_register">Déjà un compte ? <a href="/login">Connectez-vous !</a></p> 
    <?php endif?>
</form>