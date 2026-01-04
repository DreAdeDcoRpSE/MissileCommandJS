<!DOCTYPE html>
<html>
<head>
<title>Missile Command</title>
<meta charset="UTF-8">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Wallpoet&display=swap  ');

    .blink{
        animation: blinker 2s linear infinite;
    }
    @keyframes blinker {
        50% { opacity: 0; }
    }
    #canvas{
        border: 4px solid #707070ff;
        max-width: 95vw;    /* Maximum 95% of viewport width */
        max-height: 80vh;   /* Maximum 80% of viewport height */
        cursor: url(sprites/puntero.cur), default;
        background-color: #0c0c15ff;
        margin-bottom: 20px;
        width: auto;
        height: auto;
        object-fit: contain;
    }
    body{
        text-align: center;
        background-color: #191919ff;
    }
    h1{
        color: yellow;
        text-shadow: 2px 3px 7px blue;
        font-family: 'consolas';
        font-size: 60px;
        padding-top:30px;
    }
    h2{
        padding: 5px;
        color: white;
        font-family: "Wallpoet", sans-serif;
        font-weight: 900;
        font-style: normal;
        font-size: 48px;
        letter-spacing: 10px;
        margin: 0px !important;
    }
    h3{
        color: red;
        text-shadow: 2px 2px 5px black;
        font-family: 'lucida console';
        font-size: 20px;
    }
    .container{
        text-shadow: 2px 2px 5px black;
        color: rgb(221, 221, 221);
        font-family: 'lucida console';
        font-size: 24px;
        padding: 5px;
    }
    .sButton{
        background-color: #373737ff;
        border: 3px solid #00ff00ff;
        color: #ffffffff;
        box-shadow:0px 10px #000000ff;
        border-radius: 10px;
        font-family: "Wallpoet", sans-serif;
        font-size: 48px;
        font-weight: 300;
        padding: 5px 25px;
        letter-spacing: 12px;
        margin-bottom: 30px;
        margin-left: auto;
        margin-right: auto;
    }

    .mButton{
        position: fixed;
        top: 5px;
        left: 10px;
        background-color: #191919ff;
        border: 1px solid #00ff00ff;
        color: #ffffffff;
        border-radius: 5%;
        font-family: "Wallpoet", sans-serif;
        font-size: 30px;
        font-weight: 400;
        padding: 4px;
    }

        .mButton:hover {
        background-color: #2c2c2cff;
        border: 1px solid #dfffdfff;
        color: #dcdcdcff;
    }

    #roundDisplay, #scoreDisplay {
        font-size: 24px;
        color: white;
        margin: 2px 10px;
        padding: 5px;
    }

    #scoreBoard {
        width: 100%;
        text-align: center;
    }

    #roundCompleteMessage {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #000000cc;
        color: yellow;
        font-family: 'consolas';
        font-size: 24px;
        padding: 20px;
        border: 2px solid #ffffffff;
        border-radius: 10px;
        text-align: center;
        max-width: 500px;
        display: none;
        z-index: 10;
    }

    .infoBox {
        float: right;
    }
</style>
</head>

<body onload="paginaCargada()">

    <input type="hidden" id="player-username" value="coRpSE">
    <!--IMAGENES - Multiple ground sprites -->
    <img style="display: none" id="ground0" src="sprites/ground0.png">
    <img style="display: none" id="ground1" src="sprites/ground1.png">
    <img style="display: none" id="ground2" src="sprites/ground2.png">
    <img style="display: none" id="ground3" src="sprites/ground3.png">
    <img style="display: none" id="ground4" src="sprites/ground4.png">
    <img style="display: none" id="ground5" src="sprites/ground5.png">
    <img style="display: none" id="ground6" src="sprites/ground6.png">
    <img style="display: none" id="ground7" src="sprites/ground7.png">
    <img style="display: none" id="ground8" src="sprites/ground8.png">
    <img style="display: none" id="ground9" src="sprites/ground9.png">
    <img style="display: none" id="low" src="sprites/low.png">
    <img style="display: none" id="out" src="sprites/out.png">
    <img style="display: none" id="city" src="sprites/city.png">
    <img style="display: none" id="gameOver" src="sprites/gameOver.png">
    <!-- NEW IMAGES FOR BONUS TARGETS -->
    <img style="display: none" id="alien" src="sprites/alien.png">
    <img style="display: none" id="jet" src="sprites/jet.png">

    <!--audio-->
    <audio id="gameMusic" loop>
        <source src="audio/8bit-music.mp3">
    </audio>
    <audio id="disparo">
        <source src="audio/disparo.mp3">
    </audio>
    <audio id="startButton">
        <source src="audio/startButton.mp3">
    </audio>
    <audio id="explosion">
        <source src="audio/explosion.mp3">
    </audio>
    <audio id="gameOverAudio">
        <source src="audio/gameOver.mp3">
    </audio>

    <!--TEXTO-->
    <div class="mainContianer">
        <h2>MISSILE COMMAND</h2>
        <p id="scoreBoard">
            <span id="scoreDisplay">SCORE: 0</span>
            <span id="roundDisplay">ROUND: 1</span>
        </p>
        <div id="canvas-container" style="position: relative; display: inline-block;">
          <canvas id="canvas" width="888" height="624"></canvas>
          <div id="roundCompleteMessage"></div>
        </div>
        <button id="muteButton" class="mButton" type="button">🔇</button>
        <p id="vacio" class="container" style="font-size: 0px;"></p>
        <button id="start" type="button" class="blink sButton" onclick="startPulsado()"> PLAY </button>
        <p id="objective" class="container" style="display: none;">Point and shoot!</p>
    </div>
<script>

    misilesDefensivos = new Array();
    misilesOfensivos = new Array();
    ciudades = new Array();
    torretas = new Array();
    torretasFuncionales = new Array(); //Array de torretas que sí tienen munición y por tanto pueden disparar
    explosiones = new Array();
    distTorretaX = new Array();
    puntaTorretaX = new Array();
    var tilesGround = new Array();
    existingGame = false;

    // New variables for rounds and bonus targets
    var currentRound = 1;
    var missilesRemaining = 20; // Starting missiles for round 1
    var baseMissileSpeed = 0.5;
    var missileSpeed = baseMissileSpeed;
    var roundComplete = false;
    var roundCompleteTimer = null; // Timer for round completion pause

    // Bonus targets
    var jets = new Array();
    var aliens = new Array();
    var jetSpawnTimer = null;
    var alienSpawnTimer = null;
    var jetSpeed = 1.3;
    var alienSpeed = .7;

    //Scoring System
    var score = 0;
    var gameActive = false;

    //New variables for rounds
    var currentRound = 1;
    var lastGroundIndex = -1; // Track the index of the ground used in the previous round
    var missilesRemaining = 20; // Starting missiles for round 1
    var gameHasEnded = false; // Flag indicating the game has ended
    var gameRestarting = false; // Flag to signal fade-in animation to stop

    const pasosAnim = 100; //Frames per second for animations
    var velAparicion = 0.75; //Enemies per second that appear
    maxAmunittion = 10; //Maximum ammunition for turrets
    velRecarga = 0.3; //How many times per second turret ammunition is reloaded
    radioMaxExplosiones = 50;
    width = canvas.width;
    height = canvas.height;

    //Ground
    var wideGround = 148;
    var highGround = 104;

    // Array of ground images (0-9)
    var groundImages = [
        document.getElementById("ground0"),
        document.getElementById("ground1"),
        document.getElementById("ground2"),
        document.getElementById("ground3"),
        document.getElementById("ground4"),
        document.getElementById("ground5"),
        document.getElementById("ground6"),
        document.getElementById("ground7"),
        document.getElementById("ground8"),
        document.getElementById("ground9")
    ];

    // Current ground image to use
    var currentGroundImage = groundImages[0]; // Start with ground0.png

    var imgLow = document.getElementById("low");
    var imgOut = document.getElementById("out");
    var imgCity = document.getElementById("city");
    var imgAlien = document.getElementById("alien");
    var imgJet = document.getElementById("jet");

    canvas = document.getElementById("canvas");
    ctx = canvas.getContext("2d"); //Canvas context for drawing operations

    var mapa = new constructorMapa();

    class Vector2{
        constructor(x, y){
            this.x = x;
            this.y = y;
        }

        Set(x, y){
            this.x = x;
            this.y = y;
        }
        Substract(v){
            this.x = this.x - v.x;
            this.y = this.y - v.y;
        }
        Normalize(){
            var length = Math.sqrt( Math.pow(this.x,2) + Math.pow(this.y, 2) );
            this.x = this.x / length;
            this.y = this.y / length;
        }
        Equals(v){
            return(this.x - v.x == 0 && this.y - v.y == 0);
        }
        Dot(n){
            this.x *= n;
            this.y *= n;
        }
        GetDistance(point){ //Calculates distance to another point
            let a = point.y - this.y;
            let b = point.x - this.x;
            return Math.sqrt( Math.pow(a,2) + Math.pow(b,2) );
        }
    }

    class Misil{
        constructor(initPos, aimPos){ //Takes two Vector2 objects
            this.initPos = initPos; //Starting position
            this.pos = new Vector2(initPos.x, initPos.y); //Current position
            this.aimPos = aimPos; //Target position

            this.radius = 2;
            this.speed = missileSpeed; // Use current missile speed

            this.colorEstela = '#00FF00'; //Color of the missile trail
            this.colorPunto = '#fffda3ff'; //Missile color
        }

        Update(){ //Moves the projectile from start to target
            var vel = new Vector2(this.aimPos.x, this.aimPos.y);
            vel.Substract(this.pos); //velocity vector = aimPos - pos
            vel.Normalize();

            vel.Dot(this.speed); //Use current speed
            this.pos.x += vel.x;
            this.pos.y += vel.y;
        }

        Draw(){ //Draws both the projectile and the missile's trail
            this.DrawDot();
            this.DrawTrail();
        }

        DrawDot(){ //Draw a point at the location where it is.
            ctx.beginPath();
            ctx.arc(this.pos.x, this.pos.y, this.radius, 0, 2 * Math.PI);
            ctx.fillStyle = this.colorPunto;
            ctx.fill();
        }

        DrawTrail(){ //Draws the missile's trail, from start to current position.
            ctx.beginPath();
            ctx.moveTo(this.initPos.x, this.initPos.y);
            ctx.lineTo(this.pos.x, this.pos.y);
            ctx.strokeStyle = this.colorEstela;
            ctx.stroke();
        }

        ReadyToExplode(){ //Returns true if it reaches its destination
            var dist = new Vector2(this.aimPos.x, this.aimPos.y);
            dist.Substract(this.pos); //distance vector = aimPos - pos
            //If it has reached its destination (with margin of error)
            return ( (dist.x > -1 && dist.x < 1) && (dist.y > -1 && dist.y < 1) );
        }
    }

    class MisilDefensivo extends Misil{
        constructor(initPos, aimPos) {
            super(initPos, aimPos);
        }

        Update(){ //Moves the projectile from start to target.
            var vel = new Vector2(this.aimPos.x, this.aimPos.y);
            vel.Substract(this.pos); //velocity vector = aimPos - pos
            vel.Normalize();
            vel.Dot(2); //Defensive missiles are faster
            this.pos.x += vel.x;
            this.pos.y += vel.y;
            this.colorEstela = "white";
            this.colorPunto = "yellow";
        }
    }

    class Explosion {
        constructor(pos) {
            this.pos = pos;
            this.radius = 0;
            this.colors = [
                "#FFFFFF",
                "#FFFF00",
                "#FFFFFF",
                "#FF00FF",
                "#FFFFFF",
                "#00F2FF",
                "#FFFFFF",
                "#ff5d5d"
            ];
            this.cycleLength = 30; // Adjust for speed of color change
        }

        Update() {
            this.radius += 1;
        }

        Draw() {
            const maxRad = radioMaxExplosiones; // 50
            const minAlpha = 0.5; // <-- Adjust this: 0.0 = fully fade out, 1.0 = no fade. Try 0.2–0.5.
            const alpha = minAlpha + (1 - minAlpha) * (1 - this.radius / maxRad);
            const index = Math.floor(this.radius / (this.cycleLength / this.colors.length)) % this.colors.length;
            const baseColor = this.colors[index];

            // Convert hex to rgba with dynamic alpha
            const r = parseInt(baseColor.slice(1, 3), 16);
            const g = parseInt(baseColor.slice(3, 5), 16);
            const b = parseInt(baseColor.slice(5, 7), 16);

            ctx.beginPath();
            ctx.arc(this.pos.x, this.pos.y, this.radius, 0, 2 * Math.PI);
            ctx.fillStyle = `rgba(${r}, ${g}, ${b}, ${alpha})`;
            ctx.fill();
        }
    }

    class Torreta{
        constructor(x){
            this.pos = new Vector2(x, height-90)
            this.low = false;
            this.out = false;
            this.amunittion = maxAmunittion;
        }
        ReduceAmunnition(){
            //Has ammunition
            if(this.amunittion > 0)
            {
                --this.amunittion;
                this.CheckSigns();
            }
        }
        GainAmunittion(){
            if(this.amunittion < maxAmunittion){
                this.amunittion++;
                this.CheckSigns();
            }
        }
        CheckSigns(){ //Checks for "out" and "low" signs
            if(this.amunittion == 0){
                this.low = false;
                this.out = true;
            }else if(this.amunittion <= 5){
                this.low = true;
                this.out = false;
            }else{
                this.low = false;
            }
        }

        Draw(){
            var torreta = new Image();
			torreta.src = "sprites/turret.png";

            //Draw turret
            ctx.drawImage(torreta,this.pos.x, this.pos.y);

            //Draw "low" warning if true
            if(this.low)
                {ctx.drawImage(imgLow,this.pos.x - 40, this.pos.y + 40);}
            //Draw "out" warning if true
            if(this.out)
                {ctx.drawImage(imgOut,this.pos.x - 40, this.pos.y + 40);}
        }
    }

    class City{
        constructor(x){
            this.pos = new Vector2(x, height-50);
        }

        Draw(){
            ctx.drawImage(imgCity, this.pos.x, this.pos.y);
        }
    }

    // Bonus target classes
    class Jet {
        constructor() {
            // Randomly choose direction: true = from left, false = from right
            this.fromRight = Math.random() < 0.5;

            if (!this.fromRight) {
                // Spawn from left → move right
                this.pos = new Vector2(-50, getRandomInt(height / 2));
                this.speed = jetSpeed; // positive
                this.direction = 1;
            } else {
                // Spawn from right → move left
                this.pos = new Vector2(width + 50, getRandomInt(height / 2));
                this.speed = -jetSpeed; // negative
                this.direction = -1;
            }

            this.alive = true;
            this.missed = false;
            this.width = 60;
            this.height = 30;
        }

        Update() {
            this.pos.x += this.speed; // Now handles both directions

            // Remove if fully off-screen in travel direction
            if ((this.direction === 1 && this.pos.x > width + 50) ||
                (this.direction === -1 && this.pos.x < -50)) {
                this.missed = true;
                return true; // remove
            }
            return false;
        }

        Draw() {
            ctx.save(); // Save current context state

            if (this.fromRight) {
                // Flip horizontally around the jet's center
                ctx.translate(this.pos.x + this.width / 2, this.pos.y + this.height / 2);
                ctx.scale(-1, 1);
                ctx.drawImage(imgJet, -this.width / 2, -this.height / 2, this.width, this.height);
            } else {
                // Normal draw
                ctx.drawImage(imgJet, this.pos.x, this.pos.y, this.width, this.height);
            }

            ctx.restore(); // Restore original context
        }

        CheckCollision(explosion) {
            if (!this.alive) return false;
            // Check if explosion center is within jet bounds
            if (explosion.pos.x >= this.pos.x &&
                explosion.pos.x <= this.pos.x + this.width &&
                explosion.pos.y >= this.pos.y &&
                explosion.pos.y <= this.pos.y + this.height) {
                return true;
            }
            // Also check distance-based collision for better feel
            var dist = this.pos.GetDistance(explosion.pos);
            return dist < 40; // Collision radius
        }
    }

    class Alien {
        constructor() {
            this.fromRight = Math.random() < 0.5;
            if (!this.fromRight) {
                this.pos = new Vector2(-50, getRandomInt(height / 2));
                this.speed = alienSpeed;
            } else {
                this.pos = new Vector2(width + 50, getRandomInt(height / 2));
                this.speed = -alienSpeed;
            }
            this.alive = true;
            this.missed = false;
            this.width = 40;
            this.height = 40;
        }

        Update() {
            this.pos.x += this.speed;
            if ((this.speed > 0 && this.pos.x > width + 50) ||
                (this.speed < 0 && this.pos.x < -50)) {
                this.missed = true;
                return true;
            }
            return false;
        }

        Draw() {
            // No flip needed for alien (or add flip logic if desired)
            ctx.drawImage(imgAlien, this.pos.x, this.pos.y, this.width, this.height);
        }

        CheckCollision(explosion) {
            if (!this.alive) return false;
            // Check if explosion center is within alien bounds
            if (explosion.pos.x >= this.pos.x &&
                explosion.pos.x <= this.pos.x + this.width &&
                explosion.pos.y >= this.pos.y &&
                explosion.pos.y <= this.pos.y + this.height) {
                return true;
            }
            // Also check distance-based collision
            var dist = this.pos.GetDistance(explosion.pos);
            return dist < 30; // Collision radius
        }
    }

    function constructorSprite(imageSource, x, y, ancho, alto){
            var image = imageSource;
            this.x = x;
            this.y = y;
            this.ancho = ancho;
            this.alto = alto;

            //Draws the ground sprite on the given context at position x,y
            this.dibujaSprite = function(context, x, y)
            {context.drawImage(image, this.x, this.y, this.ancho, this.alto, x, y, this.ancho, this.alto);}
    }

    function constructorMapa(){
        var anchoMapa = 6; //148*6=888
        var altoMapa = 6; //104*5=624

        this.mapa =
        [   [6, 6, 6, 6, 6, 6],
            [6, 6, 6, 6, 6, 6],
            [6, 6, 6, 6, 6, 6],
            [6, 6, 6, 6, 6, 6],
            [6, 6, 6, 6, 6, 6],
            [0, 1, 4, 5, 4, 3],    ];

        //Draws the ground on the given canvas 6x6 elements of 148x104
        this.dibujaMapa = function (context)
        {
            context.clearRect(0,0, width, height);

            for ( var y = 0; y < anchoMapa; y++)
            {
                for ( var x = 0; x < altoMapa; x++)
                {tilesGround[this.mapa[y][x]].dibujaSprite(context, x*wideGround, y*highGround);}
            }
        }
    }

    // Initialize ground tiles once at startup, using current ground image
    function InitializeGroundTiles() {
        var tileID = 0;

        // First row: 6 tiles (0 to 5)
        for (var x = 0; x < 6; x++) {
            var xTile = x * wideGround;
            tilesGround[tileID] = new constructorSprite(currentGroundImage, xTile, 0, wideGround, highGround);
            tileID++;
        }

        // Second row: first tile is empty (index 6)
        tilesGround[6] = new constructorSprite(currentGroundImage, 0, highGround, wideGround, highGround);
    }

    // Update ground tiles to use a new image
    function UpdateGroundTiles() {
        var tileID = 0;

        // First row: 6 tiles (0 to 5)
        for (var x = 0; x < 6; x++) {
            var xTile = x * wideGround;
            tilesGround[tileID] = new constructorSprite(currentGroundImage, xTile, 0, wideGround, highGround);
            tileID++;
        }

        // Second row: first tile is empty (index 6)
        tilesGround[6] = new constructorSprite(currentGroundImage, 0, highGround, wideGround, highGround);
    }

    function paginaCargada(){
        InitializeGroundTiles(); //Call once at startup
        Draw();
    };

    function Draw(){

        if (gameHasEnded) {
            return; //Exit the Draw function immediately
        }

        ctx.clearRect(0, 0, width, height); //Clear canvas

        DrawGround();
        DrawMisilesDefensivos();
        DrawMisilesOfensivos();
        DrawCityes();
        DrawTorretas();
        DrawExplosiones();
        DrawBonusTargets();

        window.requestAnimationFrame(Draw); //Execute on next frame
    }

    function DrawGround() {
        mapa.dibujaMapa(ctx);
    }

    function DrawBonusTargets() {
        // Draw jets
        for(let i = jets.length - 1; i >= 0; i--) {
            if (jets[i].alive) {
                jets[i].Draw();
            }
        }

        // Draw aliens
        for(let i = aliens.length - 1; i >= 0; i--) {
            if (aliens[i].alive) {
                aliens[i].Draw();
            }
        }
    }

    function DrawMisilesDefensivos(){
        for(i = 0; i < misilesDefensivos.length; i++){
            misilesDefensivos[i].Draw();
        }
    }
    function DrawMisilesOfensivos(){
        for(i = 0; i < misilesOfensivos.length; i++){
            misilesOfensivos[i].Draw();
        }
    }
    function DrawCityes(){
        for(i = 0; i < ciudades.length; i++){
            ciudades[i].Draw();
        }
    }
    function DrawTorretas(){
        for(i = 0; i < torretas.length; i++){
            torretas[i].Draw();
        }
    }
    function DrawExplosiones(){
        for(i = 0; i < explosiones.length; i++){
            explosiones[i].Draw();
        }
    }

    function startPulsado(){
        document.getElementById("startButton").play();
        document.getElementById("gameMusic").play();

        document.getElementById("objective").style.display = "block";

        // Hide start button during gameplay
        document.getElementById("start").style.display = "none";

        StartGame();
    }

    function DesactivarBotonStart(){
        document.getElementById("start").style.display = "none";
    }

    function ActivarBotonStart(){
        document.getElementById("start").style.display = "block";
    }

    function Update(){
        if (roundComplete) return; // Pause game during round completion

        UpdateMisilesDefensivos();
        UpdateMisilesOfensivos();
        UpdateExplosiones();
        UpdateBonusTargets();
        CheckForCollisions();
        CheckBonusCollisions();
        CheckRoundCompletion();
    }

    function UpdateBonusTargets() {
        // Update jets
        for(let i = jets.length - 1; i >= 0; i--) {
            if (jets[i].Update()) {
                if (jets[i].missed && gameActive) {
                    score -= 5;
                    document.getElementById("scoreDisplay").textContent = "SCORE: " + score;
                }
                jets.splice(i, 1);
            }
        }

        // Update aliens
        for(let i = aliens.length - 1; i >= 0; i--) {
            if (aliens[i].Update()) {
                if (aliens[i].missed && gameActive) {
                    score -= 5;
                    document.getElementById("scoreDisplay").textContent = "SCORE: " + score;
                }
                aliens.splice(i, 1);
            }
        }
    }

    function CheckBonusCollisions() {
        // Check jet collisions with explosions
        for(let i = jets.length - 1; i >= 0; i--) {
            if (!jets[i].alive) continue;

            for(let j = 0; j < explosiones.length; j++) {
                if (jets[i].CheckCollision(explosiones[j])) {
                    jets[i].alive = false;
                    if (gameActive) {
                        score += 20;
                        document.getElementById("scoreDisplay").textContent = "SCORE: " + score;
                    }
                    // Create explosion at jet position
                    explosiones.push(new Explosion(new Vector2(jets[i].pos.x + jets[i].width/2, jets[i].pos.y + jets[i].height/2)));
                    jets.splice(i, 1);
                    break;
                }
            }
        }

        // Check alien collisions with explosions
        for(let i = aliens.length - 1; i >= 0; i--) {
            if (!aliens[i].alive) continue;

            for(let j = 0; j < explosiones.length; j++) {
                if (aliens[i].CheckCollision(explosiones[j])) {
                    aliens[i].alive = false;
                    if (gameActive) {
                        score += 20;
                        document.getElementById("scoreDisplay").textContent = "SCORE: " + score;
                    }
                    // Create explosion at alien position
                    explosiones.push(new Explosion(new Vector2(aliens[i].pos.x + aliens[i].width/2, aliens[i].pos.y + aliens[i].height/2)));
                    aliens.splice(i, 1);
                    break;
                }
            }
        }
    }

    function CheckRoundCompletion() {
        // Only check if game is still active and NOT ending
        if (roundComplete || !gameActive || ciudades.length === 0 || gameHasEnded) {
            return;
        }

        if (misilesOfensivos.length === 0 && missilesRemaining <= 0) {
            // Round complete, show completion message
            roundComplete = true;
            clearInterval(time);
            clearInterval(generadorEnemigos);
            clearInterval(recargarTorretas);

            // Show round completion message
            const messageDiv = document.getElementById("roundCompleteMessage");
            messageDiv.innerHTML = `Round ${currentRound} Completed!<br>Next round starts in <span id="countdown">3</span>`;
            messageDiv.style.display = "block";

            // Start countdown
            let countdown = 5;
            document.getElementById("countdown").textContent = countdown;

            roundCompleteTimer = setInterval(() => {
                countdown--;
                if (countdown > 0) {
                    document.getElementById("countdown").textContent = countdown;
                } else {
                    // Start next round
                    clearInterval(roundCompleteTimer);
                    messageDiv.style.display = "none";
                    StartNextRound();
                }
            }, 1000);
        }
    }

    function StartNextRound() {
        //Select a new ground index, ensuring it's different from the last one
        var newGroundIndex;
        do {
            newGroundIndex = Math.floor(Math.random() * groundImages.length); // Pick a random index
        } while (newGroundIndex === lastGroundIndex && groundImages.length > 1); // Repeat if it matches the last one (and we have more than 1 option)

        // Update the current ground image and record the new index
        currentGroundImage = groundImages[newGroundIndex];
        lastGroundIndex = newGroundIndex; //Store this index for next round's check

        // Clear all active projectiles and bonus targets
        ClearArray(misilesDefensivos);
        ClearArray(misilesOfensivos);
        ClearArray(explosiones);
        ClearArray(jets); //Clear jets from the previous round
        ClearArray(aliens); //Clear aliens from the previous round

        currentRound++;
        missilesRemaining = 15 + (currentRound * 5); // 20, 25, 30, 35...
        missileSpeed = baseMissileSpeed + (currentRound - 1) * 0.1; // Increase speed each round
        velAparicion = 0.75 + (currentRound - 1) * 0.1; // Increase spawn rate

        //Update round display
        document.getElementById("roundDisplay").textContent = "ROUND: " + currentRound;

        roundComplete = false;

        //Rebuild ground tiles with the new image
        UpdateGroundTiles(); //Call this after setting currentGroundImage

        //Restart game loops
        time = setInterval(Update, 1000/pasosAnim);
        generadorEnemigos = setInterval(LaunchOffensiveMisil, 1000/velAparicion);
        recargarTorretas = setInterval(RecargarTorretas, 1000/velRecarga);
    }

    function UpdateMisilesDefensivos(){
        for(i = 0; i < misilesDefensivos.length; i++){
            misilesDefensivos[i].Update();

            if(misilesDefensivos[i].ReadyToExplode()){ //When missile reaches destination
                Explode(misilesDefensivos, i);
            }
        }
    }
    function UpdateMisilesOfensivos(){
        for(i = 0; i < misilesOfensivos.length; i++){
            misilesOfensivos[i].Update();

            if(misilesOfensivos[i].ReadyToExplode()){ //When missile reaches destination
                DestroyCityByPos(misilesOfensivos[i].aimPos); //Destroy targeted city
                Explode(misilesOfensivos, i); //Explode
                missilesRemaining--; //Reduce remaining missiles
                CheckForEndGame(); //Check if player lost
            }
        }
    }
    function UpdateExplosiones(){
        for(i = 0; i < explosiones.length; i++){
            explosiones[i].Update();
            //If explosion reaches max radius, remove it
            if(explosiones[i].radius >= radioMaxExplosiones){ removeFromArray(explosiones, i); }
        }
    }
    function CheckForCollisions(){ //Check for each offensive missile if it collided with explosion or defensive missile
        for(i = 0; i < misilesOfensivos.length; i++){
            for(j = 0; j < explosiones.length; j++){
                if(calcularColision(misilesOfensivos[i], explosiones[j])){
                    // Player destroyed an enemy missile → +10 points
                    if (gameActive) {
                        score += 10;
                        document.getElementById("scoreDisplay").textContent = "SCORE: " + score;
                    }
                    missilesRemaining--; // Reduce remaining missiles
                    Explode(misilesOfensivos, i);
                    break; // avoid double-counting
                }
            }
        }
    }
    function CheckForEndGame(){
        if(ciudades.length == 0){ //If no cities remain
            EndGame();
        }
    }


    function Explode(misileArr, i){ //Given missile array and index, detonate missile at that index
        explosiones.push( new Explosion(misileArr[i].pos) ); //Create explosion
        removeFromArray(misileArr, i); //Remove missile
        document.getElementById("explosion").play();
    }
    function DestroyCityByPos(targetPos){ //Given position, destroy city at that position
        for(p = 0; p < ciudades.length; p++){
            var cityPos = new Vector2(ciudades[p].pos.x + 30, ciudades[p].pos.y); //Missiles target 30 units right, so correct

            if(cityPos.Equals(targetPos)){ //If given position matches city position
                removeFromArray(ciudades, p); //Remove city
            }
        }
    }

    function getRandomInt(max)
    {return Math.floor( Math.random() * max);}

    function ClearArray(arr){
        arr.splice(0, arr.length);
    }
    function copyArray(a, b){ //Copy content of b to a
        ClearArray(a);
        for(i = 0; i < b.length; i++)
            {a.push(b[i]);}
    }
    function removeFromArray(arr, i){ //Given array and index, remove element at that index
        if(i < arr.length && i >= 0){ //If index is valid
            arr.splice(i, 1);
        }
    }

    function StartGame(){
        if(existingGame){ ClearGame(); }

        // Add touch event support
        canvas.addEventListener('touchstart', function(event) {
            event.preventDefault(); // Prevent scrolling
            var touch = event.touches[0];
            var mouseEvent = new MouseEvent("click", {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            LaunchMisilDefensivo(mouseEvent);
        }, {passive: false});

        //Reset the gameHasEnded flag BEFORE clearing/resuming drawing
        gameHasEnded = false;

        //Clear the canvas IMMEDIATELY to remove the game-over image
        //This must happen BEFORE the game state is set up and BEFORE the Draw loop potentially resumes drawing game elements
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Reset game state
        score = 0;
        currentRound = 1;
        lastGroundIndex = -1; // Reset the last index when starting a new game
        missilesRemaining = 20;
        missileSpeed = baseMissileSpeed;
        velAparicion = 0.75;
        roundComplete = false;
        gameActive = true;

        //Select a random ground index for the first round
        var firstRoundGroundIndex = Math.floor(Math.random() * groundImages.length);
        currentGroundImage = groundImages[firstRoundGroundIndex];
        lastGroundIndex = firstRoundGroundIndex; //Record this index for the *next* round's check

        UpdateGroundTiles(); // Rebuild tiles with the new image

        document.getElementById("scoreDisplay").textContent = "SCORE: " + score;
        document.getElementById("roundDisplay").textContent = "ROUND: " + currentRound;

        GenerateCityes();
        GenerateTorretas();

        //Launch missile when clicking on canvas
        canvas.addEventListener("click", LaunchMisilDefensivo, false);

        //Start update loop
        time = setInterval(Update, 1000/pasosAnim);

        //Start enemy missile generator loop
        generadorEnemigos = setInterval(LaunchOffensiveMisil, 1000/velAparicion);

        //Start turret reload loop
        recargarTorretas = setInterval(RecargarTorretas, 1000/velRecarga);

        // Start bonus target timers (jets less frequent - every 15 seconds instead of 5)
        if (jetSpawnTimer) clearInterval(jetSpawnTimer);
        if (alienSpawnTimer) clearInterval(alienSpawnTimer);
        jetSpawnTimer = setInterval(SpawnJet, 15000); // Spawn jet every 15 seconds starting round 3 (half as frequent)
        alienSpawnTimer = setInterval(SpawnAlien, 20000); // Spawn alien every 20 seconds starting round 5
        existingGame = true;

        //Ensure the main drawing loop starts agaain
        window.requestAnimationFrame(Draw);
    }

    function ClearGame(){
        ClearArray(misilesDefensivos);
        ClearArray(misilesOfensivos);
        ClearArray(ciudades);
        ClearArray(torretas);
        ClearArray(torretasFuncionales);
        ClearArray(explosiones);
        ClearArray(distTorretaX);
        ClearArray(puntaTorretaX);
        ClearArray(jets);
        ClearArray(aliens);
        clearInterval(time);
        clearInterval(generadorEnemigos);
        clearInterval(recargarTorretas);
        if (jetSpawnTimer) clearInterval(jetSpawnTimer);
        if (alienSpawnTimer) clearInterval(alienSpawnTimer);
        if (roundCompleteTimer) clearInterval(roundCompleteTimer);
        document.getElementById("roundCompleteMessage").style.display = "none";
        existingGame = false;
    }

    function GenerateCityes(){ //Create cities at specified x positions
        ciudades.push(new City(150));
        ciudades.push(new City(230));
        ciudades.push(new City(310));
        ciudades.push(new City(510));
        ciudades.push(new City(590));
        ciudades.push(new City(670));
    }
    function GenerateTorretas(){
        torretas.push(new Torreta(79));
        torretas.push(new Torreta(433));
        torretas.push(new Torreta(785));
        copyArray(torretasFuncionales, torretas); //Copy turrets to functional turrets
    }

    function EndGame(){
        // Clear any lingering round-complete UI
        const roundMsg = document.getElementById("roundCompleteMessage");
        if (roundMsg) roundMsg.style.display = "none";

        console.log("Game over");
        clearInterval(time);
        clearInterval(generadorEnemigos);
        clearInterval(recargarTorretas);
        if (jetSpawnTimer) clearInterval(jetSpawnTimer);
        if (alienSpawnTimer) clearInterval(alienSpawnTimer);
        if (roundCompleteTimer) clearInterval(roundCompleteTimer);
        canvas.removeEventListener("click", LaunchMisilDefensivo);
        //Set flag that game is over and stop the music
        gameHasEnded = true;
        document.getElementById("gameMusic").pause();

        const gameOverAudio = document.getElementById("gameOverAudio");

        //Try to play the game over audio
        if (gameOverAudio) {
            const playPromise = gameOverAudio.play();
            if (playPromise !== undefined) {
                playPromise.catch(e => console.warn("Game over audio play failed:", e));
            }
        } else {
            console.warn("Audio element with ID 'gameOverAudio' not found!");
        }

        //Create an Image object
        const gameOverImg = new Image();
        gameOverImg.src = "sprites/gameOver.png"; // Set the source

        //Function to draw the image after it loads
        gameOverImg.onload = () => {
            //Clear the canvas first
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            //Draw the image to fill the canvas
            ctx.drawImage(gameOverImg, 0, 0, canvas.width, canvas.height);
        };

        //Show start button again (this happens regardless of the canvas drawing)
        ActivarBotonStart();
        submitFinalScore();
    }

    function LaunchMisilDefensivo(event){
        //Get canvas top-left position relative to viewport
        var posicionesCanvas = canvas.getBoundingClientRect();

        // Calculate the scale ratio between visual and actual canvas size
        var scaleX = canvas.width / posicionesCanvas.width;
        var scaleY = canvas.height / posicionesCanvas.height;

        // Calculate pointer positions and scale them to match the actual canvas size
        var posCanvaX = (event.clientX - posicionesCanvas.left) * scaleX + 11;
        var posCanvaY = (event.clientY - posicionesCanvas.top) * scaleY + 40;

        var objective = new Vector2(posCanvaX, posCanvaY);

        if(torretasFuncionales.length > 0){
            misilesDefensivos.push( new MisilDefensivo(selectTorreta(posCanvaX), objective));
            document.getElementById("disparo").play();
        } else {
            document.getElementById("objective").innerHTML = "<span style='color: #FF0000; font-weight: 900;'>ALERT!</span> No ammunition remaining in any turret.";
        }
    }


    //Select turret to launch defensive missile based on target x position
    function selectTorreta(posObjetivoX){
        //Get distance from each turret to target
        for(var i = 0; i < torretasFuncionales.length; i++)
        {
            puntaTorretaX[i] = torretasFuncionales[i].pos.x + 7;
            distTorretaX[i] = Math.abs(puntaTorretaX[i] - posObjetivoX);
        }

        //Get shortest distance and turret ID
        var ID = 0;
        var valor = distTorretaX[0];

        for(var i = 1; i < distTorretaX.length; i++)
        {
            if(distTorretaX[i]<valor)
            {
                valor = distTorretaX[i];
                ID = i;
            }
        }

        //Fire from that turret and reduce ammunition
        var posDisparo = new Vector2(puntaTorretaX[ID], torretasFuncionales[ID].pos.y);
        torretasFuncionales[ID].ReduceAmunnition();

        //If out of ammunition, deactivate and remove distance
        if(torretasFuncionales[ID].amunittion <= 0)
            {
                removeFromArray(torretasFuncionales, ID);
                removeFromArray(distTorretaX, ID);
            }

        return posDisparo; //return turret position
    }

    function LaunchOffensiveMisil(){
        if(ciudades.length > 0 && missilesRemaining > 0 && !roundComplete){
            misilesOfensivos.push(new Misil(new Vector2(getRandomInt(width), 0), TargetRandomCity()));
            missilesRemaining--;
        }
    }

    function SpawnJet() {
        if (gameActive && currentRound >= 3 && !roundComplete) {
            jets.push(new Jet());
        }
    }

    function SpawnAlien() {
        if (gameActive && currentRound >= 5 && !roundComplete) {
            aliens.push(new Alien());
        }
    }

    function RecargarTorretas(){
        for(let i = 0; i < torretas.length; i++){
            const wasEmpty = (torretas[i].amunittion === 0);
            torretas[i].GainAmunittion();

            // If it just became usable again (was empty, now has ammo)
            if (wasEmpty && torretas[i].amunittion > 0) {
                // Add it back to functional turrets
                torretasFuncionales.push(torretas[i]);

                // ✅ Clear the "no ammo" alert message
                const objectiveEl = document.getElementById("objective");
                if (objectiveEl.innerHTML.includes("No ammunition remaining")) {
                    objectiveEl.innerHTML = "Point and shoot!";
                }
            }
        }
    }

    function TargetRandomCity(){
        target = ciudades[ getRandomInt(ciudades.length) ]; //Select random city
        var posTarget = new Vector2(target.pos.x+30,target.pos.y); //Vector correcting target to city center
        return posTarget; //Return city position
    }

    function calcularColision(c1, c2){ //Returns true if collision between two circles
        var radios = c1.radius + c2.radius;
        var dist = c1.pos.GetDistance(c2.pos);
        return dist < radios; //Collision if distance < sum of radii
    }

    function submitFinalScore() {
        const username = document.getElementById('player-username')?.value || 'guest';
        const data = 'user=' + encodeURIComponent(username) + '&score=' + encodeURIComponent(score);

        // Send to a simple PHP logger
        fetch('log_score.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: data
        })
        .then(response => response.text())
        .then(msg => console.log('Log result:', msg))
        .catch(err => console.warn('Log failed:', err));
    }

    let musicMuted = false;
    const muteButton = document.getElementById("muteButton");
    const gameMusic = document.getElementById("gameMusic");

    muteButton.addEventListener("click", function () {
        if (musicMuted) {
            // Unmute
            gameMusic.muted = false;
            muteButton.textContent = "🔇";
            musicMuted = false;
        } else {
            // Mute
            gameMusic.muted = true;
            muteButton.textContent = "🔊";
            musicMuted = true;
        }
    });
</script>
</html>