 <div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">music♫Box</a>
                </div>
                
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="/">Principal</a></li>                        
						<li><a href="/contact">Contacto</a></li>
                        <li>{{link_to("uploads/create", 'Subir Archivo', $attributes = array(), $secure = null);}}</li>
                        
                    </ul> 
                </div>
            </div>
</div>


