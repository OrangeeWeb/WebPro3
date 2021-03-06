// @codekit-prepend "prototype.js"

var info = 0;
function upload(files){
    ajax('profile/image', {file : files[0], '_token' : elm('[name=_token]').value, '_method' : 'post'}, function(data){
        data = JSON.parse(data);
        window.console.log(data);
        
        if(isset(data.error)){
            elm('.error').innerHTML = '<h2>'+data.error+'</h2>';
        } else {
            
            elm('#drop-container').style.backgroundImage = "url('"+data.path+"')";
        
        }
    }, function(e){
        // Loading % text
        var p = Math.floor( (e.loaded / e.total) * 100 );
        if(info === 0){
            info++;
        }
        if(p >= 100){
            document.getElementById("svg").setAttribute("class", "pie-chart processing");
        }
        elm('[data-percent]').setAttribute("data-percent", p);
        elm('tspan').textContent = p+"%";
    });
    
}

elm("#drop-container").onDrop(function(files){
    if(files.length > 0)  upload(files);
}).onDragOver(function(){
    this.className = "drop active";
    
}).onDragLeave(function(){
    this.className = "drop";
    
});

elm("#file").addEventListener('change', function(){
    if(this.files.length > 0) upload(this.files);
});