<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style type="text/css">
		span {display: block;}
		div {margin-bottom: 10px;}
		button {margin-right: 5px;}
	</style>
</head>
<body>

<input type="text" name="">
<button>buscar</button>
<hr>
<div class="player"></div>
<div class="content"></div>

<script type="text/javascript">
	window.audio = document.createElement('audio');
	
	function d2(num) { return num.toString().padStart(2, '0'); }

	function getHMS(milliseconds){
		
		let seconds = Math.floor(milliseconds / 1000);
		let minutes = Math.floor(seconds / 60);
		let hours = Math.floor(minutes / 60);

		seconds = seconds % 60;
		minutes = seconds >= 30 ? minutes + 1 : minutes;
		minutes = minutes % 60;
		hours = hours % 24;

		if(d2(hours) == "00"){
			return d2(minutes)+":"+d2(seconds);
		}else{
			return d2(hours)+":"+d2(minutes)+":"+d2(seconds);
		}

		
	}

	function generate_content(data){
		let content = document.querySelector('.content');

		content.innerHTML = '';

		data.forEach(item=>{
			let div = document.createElement('div');
			let img = document.createElement('img');
			let title = document.createElement('span');
			let btnPlay = document.createElement('button');
			let btnPause = document.createElement('button');
			let btnDownload = document.createElement('button');

			img.src = item.artwork_url;
			title.textContent = item.title+" - "+getHMS(item.full_duration);
			btnPlay.textContent = "play";
			btnPause.textContent = "pause";
			btnDownload.textContent = "download";

			btnPlay.addEventListener('click', e=>{
				let data = new FormData();
				data.append('progressive_stream', item.progressive)

				fetch('apisearch_stream.php', {
					method: 'post',
					body: data
				})
				.then(res=>res.json())
				.then(stream=>{
					audio.src = stream;
					audio.play()
				})

				let player = document.querySelector('.player')
				audio.controls = true;
				player.appendChild(audio)
			})

			btnPause.addEventListener('click', e=>{
				audio.pause();
			})

			btnDownload.addEventListener('click', e=>{
				let data = new FormData();
				data.append('progressive_stream', item.progressive)

				fetch('apisearch_stream.php', {
					method: 'post',
					body: data
				})
				.then(res=>res.json())
				.then(stream=>{
					
					fetch(stream)
					.then(st=>st.blob())
					.then(st=>{
						let blob = URL.createObjectURL(st);
						let a = document.createElement('a')
						a.href = blob;
						a.download = item.title + " (raqdevs@raq-php-soundcloud).mp3"
						a.click()
					})
					
				})
			})

			div.appendChild(img)
			div.appendChild(title)
			div.appendChild(btnPlay)
			div.appendChild(btnPause)
			div.appendChild(btnDownload)

			content.appendChild(div)

		})
	}

	let button = document.querySelector('button');
	let input = document.querySelector('input');

	button.addEventListener('click', e=>{
		let data = new FormData();
		data.append('q', input.value)

		fetch('apisearch.php', {
			method: 'post',
			body: data
		})
		.then(res=>res.json())
		.then(data=>{
			console.log(data)
			generate_content(data)
		})

	})
</script>

</body>
</html>