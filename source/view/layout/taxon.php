<a href="/taxon/item/{{$tax['taxonID']}}">
    <article class="col-12 taxon-prew">  
     <div class="recipie-preview" style="background-image: url('{{$tax['thumbnail']}}');"></div>  
            <div class="content">
                <h3>{{ ucfirst($tax['navn']) }}</h3>
                <p><em>{{$tax['scientificName']}}</em></p>
            </div>     
    </article>
</a>