// Object with data output methods
let output = {
	// Menu output
	menu: function() {
		// Data validation and concatenation
		if (auth.role == "guest") { 
			out = `
				<a onclick="route.redirect('auth/register')">Регистрация</a>
				<a onclick="route.redirect('auth/login')">Авторизация</a>
			`;
		}
		else if (auth.role == "user" || auth.role == "admin" || auth.role == "moderator") {
			out = ``;
			if (auth.role == "admin" || auth.role == "moderator") out += `<a onclick="route.redirect('moderation')">Модерация</a>`;
			out += `
				<a onclick="route.redirect('profile')">Личный кабинет</a>
				<a onclick="auth.logout()">Выйти</a>
			`;
		}
		// Data output
		$("#menu").html(out);
	},

	// Output users data
	users_data: function(data) {
		out = `<div class="head"><h4>ID</h4><h4>Username</h4><h4>Email</h4><h4>Login</h4><h4>Actions</h4></div>`;
		data.forEach(user => {
			out += `
				<div class="user">
					<div class="id">${user.user_id}</div>
					<div class="username">${user.username}</div>
					<div class="email">${user.email}</div>
					<div class="login">${user.login}</div>
					<div class="actions"><a class="underline">Del</a> | <a class="underline">Ban</a> | <a class="underline">Verify</a></div>
				</div>
			`;
		});
		$("#user_inform").html(out);
	},

	// Output of layout of personal data
	personal_data_layout: function() {
		out = `
			<h2>Информация о пользователе</h2><br>
			<div id="personal_inform"></div>
		`;
		$(".left .wrap .tab_content").html(out);
		out = `
			<nav class="actions">
				<h2>Действия</h2>
				<hr><br>
				<h4>Загрузить изображение профиля</h4>
				<form id="update_picture">
					<input type="file" name="picture">
					<p class="notice">Изображение должно весить не более 1мб</p>
					<a onclick="query.update_user_avatar()" class="underline">Загрузить</a>
				</form>
			</nav>
		`;
		$(".right .wrap .tab_content").html(out);
	},

	// Output of the user's personal data
	personal_data_inform: function(data) {
		out = ""; if (data.image != null) out += `<div class="image"><img src="server/${data.image}" /></div>`;
		out += `
			<div class="inform">
				<h2>${data.username}</h2>
				<p><b>Роль:</b> ${data.role}</p>
			</div>
		`;
		$("#personal_inform").html(out);
	},

	// Output form add novel
	form_add_novel: function() {
		out = `
			<h2>Добавление новеллы</h2>
			<div class="add_novel">
				<!-- Adding a novel -->
				<form onsubmit="return add_novel()">
					<!-- Novel titles -->
					<fieldset>
						<legend>Названия</legend>
						<p class="error" id="title"></p>
						<input type="text" placeholder="Название" name="title">
						<input type="text" placeholder="Оригинальное название" name="original_title">
						<input type="text" placeholder="Альтернативное название" name="alternative_title">
					</fieldset>
					<!-- Novel images -->
					<fieldset>
						<legend>Изображения</legend>
						<p>Обложка новеллы</p>
						<p class="notice">Изображение должно весить не более 1мб</p>
						<p class="error" id="cover"></p>
						<input type="file" name="cover">
						<p>Скриншот новеллы</p>
						<p class="error" id="screenshot"></p>
						<p class="notice">Изображения должны весить не более 1мб</p>
						<div class="part">
							<input type="file" id="screenshot">
							<input type="button" value="Добавить" onclick="add_screenshot()">
						</div>
						<div class="screenshots"></div>
					</fieldset>
					<!-- Developer, release year and description -->
					<fieldset>
						<legend>Разработчик, год релиза и описание</legend>
						<p class="error" id="developer"></p>
						<input type="text" placeholder="Разработчик" name="developer">
						<p class="error" id="year_release"></p>
						<input type="number" placeholder="Год релиза" name="year_release">
						<p class="error" id="description"></p>
						<textarea placeholder="Описание" name="description"></textarea>
					</fieldset>
					<!-- Genres -->
					<fieldset>
						<legend>Жанры</legend>
						<p class="error" id="genres"></p>
						<div class="part">
							<select name="genres"></select>
							<input type="button" value="Добавить" onclick="add_genre()">
						</div>
						<div class="genres"></div>
					</fieldset>
					<!-- Lists -->
					<fieldset>
						<legend>Списки</legend>
						<p class="error" id="type"></p>
						<select name="type">
							<option selected disabled>Тип</option>
							<option value="Новелла с выборами">Новелла с выборами</option>
							<option value="Кинетическая новелла">Кинетическая новелла</option>
						</select>
						<p class="error" id="duration"></p>
						<select name="duration">
							<option selected disabled>Продолжительность</option>
							<option value="Менее 2 часов">Менее 2 часов</option>
							<option value="2-10 часов">2-10 часов</option>
							<option value="10-30 часов">10-30 часов</option>
							<option value="30-50 часов">30-50 часов</option>
							<option value="Более 50 часов">Более 50 часов</option>
						</select>
						<p class="error" id="platforms"></p>
						<div class="part">
							<select name="platforms">
								<option selected disabled>Платформа</option>
								<option value="Windows">Windows</option>
								<option value="Linux">Linux</option>
								<option value="Mac">Mac</option>
								<option value="Android">Android</option>
								<option value="iOS">iOS</option>
								<option value="Другое">Другое</option>
							</select>
							<input type="button" value="Добавить" onclick="add_platform()"></input>
						</div>
						<div class="platforms"></div>
						<p class="error" id="age_rating"></p>
						<select name="age_rating">
							<option selected disabled>Возрастной рейтинг</option>
							<option value="G">G</option>
							<option value="PG">PG</option>
							<option value="PG-13">PG-13</option>
							<option value="R">R</option>
							<option value="NC-17">NC-17</option>
						</select>
						<p class="error" id="country"></p>
						<select name="country">
							<option selected disabled>Страна</option>
							<option value="Россия">Россия</option>
							<option value="Япония">Япония</option>
							<option value="США">США</option>
						</select>
						<p class="error" id="language"></p>
						<select name="language">
							<option selected disabled>Язык</option>
							<option value="Русский">Русский</option>
							<option value="Японский">Японский</option>
							<option value="Английский">Английский</option>
						</select>
					</fieldset>
					<input type="submit" value="Отправить на модерацию">
				</form>
			</div>
		`;
		$(".left .wrap .tab_content").html(out);
		out = `<div id="preview"></div>`;
		$(".right .wrap .tab_content").html(out);
	},

	// Output of novels
	search_novels: function(data, state=false, moder=false) {
		out = ""; status = ""; actions = ""; title = "";
		if(data == null) return $("#wrap_novels").html(`<h3>На данный момент контент отсутствует</h3>`);
		if (data.novels.length == 0) out = `<h3>На данный момент контент отсутствует</h3>`;
		else {
			// Data concatenation
			data.novels.forEach(novel => {
				title = (novel.state == 1) ? `<a class="underline" onclick="route.redirect('novel', ${novel.novel_id})"><h3>${novel.title}</h3></a>` :`<h3>${novel.title}</h3>`;
				actions = (moder) ? `<p><a class="underline" onclick="route.redirect('moderation/preview', ${novel.novel_id})">Предпросмотр</a> | <a class="underline" onclick="query.approve_novel('${novel.novel_id}')">Одобрить</a></p>` : "";
				if(state) status = (novel.state == 0) ? `<p><b>Состояние:</b> <font color="red">На модерации</font></p>` : `<p><b>Состояние:</b> <font color="green">Одобрена</font></p>`;
				else status = "";
				out += `
					<div class="novel">
						<div class="cover">
							<img src="server/${novel.cover.path_to_image}">
						</div>
						<div class="inform">
							${title} ${actions} ${status}
							<p><b>Год релиза:</b> ${novel.year_release}</p>
							<p><b>Тип:</b> ${novel.type}</p>
							<p><b>Платформа:</b> ${novel.platforms}</p>
							<p><b>Продолжительность:</b> ${novel.duration}</p>
							<p><b>Жанры:</b> ${novel.genres}</p>
						</div>
					</div>
				`;
			});
		}
		// Data output
		$("#wrap_novels").html(out);
	},

	// Novel data output
	novel_data_layout: function(data) {
		title = (data.original_title == "") ? data.title : data.title + " | " + data.original_title;
		out = `
			<div class="left">
				<h2>${title}</h2>
				<div class="slider shell">
					<div class="slides"></div>
					<div class="images"></div>
				</div>
				<div class="wrap_description shell">
					<h3>Описание</h3>
					<p>${data.description}</p>
				</div>
				<div class="wrap_comments shell">
					<h3>Комментарии:</h3>
					<div class="form"></div>
					<div class="comments"></div>
				</div>
			</div>
			<div class="right">
				<div class="cover">
					<img src="server/${data.cover.path_to_image}" />
				</div><hr>
				<div class="inform">
					<p><b>Год релиза:</b> ${data.year_release}</p>
					<p><b>Тип:</b> ${data.type}</p>
					<p><b>Платформа:</b> ${data.platforms}</p>
					<p><b>Продолжительность:</b> ${data.duration}</p>
					<p><b>Жанры:</b> ${data.genres}</p>
					<p><b>Разработчик:</b> ${data.developer}</p>
					<p><b>Страна:</b> ${data.country}</p>
					<p><b>Язык:</b> ${data.language}</p>
				</div><hr>
			</div>
		`;
		$(".wrap_novel").html(out);
		// 
		this.novel_data_screenshots(data.screenshots);
		this.novel_data_comments_form();
		query.get_comments();
	},

	// Output screenshots of the novel
	novel_data_screenshots: function(data) {
		out = ``;
		// Data concatenation
		data.forEach((screenshot, i) => out += `<img class="slide_${i}" src="server/${screenshot.path_to_image}" />`);
		// Output data
		$(".slides").html(out);
		// Additionally
		out += `<div class="outline"></div>`;
		$(".images").html(out);
		$(".slides").wrapInner(`<div class="flip_wrap">`);
		// Slider call
		slider.start();
	},

	// Output comments form of the novel
	novel_data_comments_form: function(data) {
		if (auth.role == "user" || auth.role == "mdoerator" || auth.role == "admin") {
			out = `
				<form id="add_comment" onsubmit="return query.add_comment()">
					<p class="error" id="comment"></p>
					<div class="part">
						<textarea placeholder="Ваш комментарий" name="comment"></textarea>
						<div class="buttons">
							<input type="submit" value="Добавить">
							<input type="reset" value="Очистить">
						</div>
					</div>
				</form><hr>
			`;
		} else out = `<p>Войдите чтобы оставить комментарий</p>`;
		$(".wrap_comments .form").html(out);
	},

	// Output comments of the novel
	novel_data_comments: function(data) {
		out = ``;
		if(data.length == 0) out = `<h3>Комментарии отсутствуют</h3>`;
		else {
			data.forEach(comment => {
				avatar = (comment.user.avatar != null) ? `<div class="avatar"><img src="server/${comment.user.avatar.path_to_image}" /></div>` : ``;
				del = (localStorage.getItem("user_id") == comment.user.user_id) ? `<p class="notice"><a onclick="query.delete_comment(${comment.comment_id})">Удалить</a></p>` : ``;
				out += `
					<div class="comment">
						${avatar}
						<div class="data">
							<div class="head"><h3>${comment.user.username}</h3>${del}</div>
							<p>${comment.content}</p>
						</div>
					</div>
				`;
			});
		}
		$(".wrap_comments .comments").html(out);
	}

}