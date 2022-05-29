$(function () {
	let favorite = $('.btn '); 
	let followerCount = $('.followerCount '); 
    let followUserId; 
    favorite.on('click', function () { //onはイベントハンドラー
		let $this = $(this); 
		followUserId = $this.data('user-id'); 
		console.log(followUserId);
		//ajax処理スタート
		$.ajax({
			headers: { //HTTPヘッダ情報をヘッダ名と値のマップで記述
			'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
			},  //↑name属性がcsrf-tokenのmetaタグのcontent属性の値を取得
			url: '/users/follow/' + followUserId , //通信先アドレスで、このURLをあとでルートで設定します
			method: 'POST', //HTTPメソッドの種別を指定します。1.9.0以前の場合はtype:を使用。
			data: { //サーバーに送信するデータ
			'userId': followUserId //いいねされた投稿のidを送る
			},
		})
		//通信成功した時の処理
		.done(function (data) {
			$this.toggleClass('btn-primary btn-danger'); //likedクラスのON/OFF切り替え。
			$('.btn-danger').text('フォロー解除');
			$('.btn-primary').text('フォローする');
			followerCount.text(data.followerCount);
		})
		//通信失敗した時の処理
		.fail(function () {
			console.log('fail'); 
		});
	});
});
