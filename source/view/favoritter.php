@layout('layout.head')
<main>
    <section class="row primary-header" style="background-image: url('/assets/img/recipis/oyster.jpg')">
        <h1 class="primary-header-text center" style="color: #525252;">Favoritter</h1>
    </section>
	<section class="container">
		<div class="row row--line">
		    <div class="col-8">
		   <ul class="list-simple--horisontal">
		       <li><a href="/recipie/insert">Lag Ny Oppskrift</a></li>
		       <li><a href="/profile">Profil</a></li>
		   </ul>
		  </div>
		  <div class="col--right">
		   <ul class="list-simple--horisontal">
		       <li><a href="/profile/update">Rediger profil</a></li>
		   </ul>
		  </div>
		</div>
		<div class="row">
			<h3 class="page-header"> Dine favoritter </h3>
				@foreach($recipe as $res)
		    <div class="col-3 col-m-6 related-res">
		        @layout('layout.recipie', ['res' => $res])
	   		 </div>
			@endforeach
		</div>
</main>

@layout('layout.scripts')
@layout('layout.foot')
