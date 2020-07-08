<div class="textwrapper">
	<a href="include-long.htm" onclick="return hs.htmlExpand(this, { objectType: 'ajax', contentId: 'highslide-html-8' } )"
			class="highslide">
	</a>
	<div class="highslide-html-content" id="highslide-html-8" style="padding: 15px; width: auto">
	    <div class="highslide-body" style="padding: 10px"></div>
	</div>

</div>

<h2>Диагностика - Версия 3 - ВЕРИТЬ осторожно. В разработке.</h2>

<div id="client">
	<div id="client-name">
		<table width="100%" class="tbl">
			<tr>
				<th align="left" style="font-size:20px;">{CLIENT}</th>
				<th>&nbsp;</th>
				<th align="right">Состояние на: {CURRENTDATE} {CURRENTTIME}</th>
			</tr>
			<!-- BEGIN noerrors -->
			<tr>
				<td colspan="3" style='text-align:center; background-color:#aaFFaa;'>
					<br />Сервера работают с непостигнутыми ошибками.<br /><br />
				</td>
			</tr>
			<!-- END noerrors -->
			<!-- BEGIN errors -->
			<tr>
				<td colspan="3" style='text-align:center; cursor:pointer; padding:20px; {errors.STYLE}' OnClick="ShowHideDiv('ErrorLevel{errors.LEVEL}')" title="ErrorLevel: {errors.LEVEL}">
					{errors.CAPTION}
					<!-- BEGIN alert -->
					<div id="ErrorLevel{errors.LEVEL}" style="display:none;padding-top:20px;">
						<table align="center" class="tbl">
							<tr><th>Сервер</th><th>Сервис</th><th>Параметр</th><th>Значение</th></tr>
							<!-- BEGIN row -->
							<tr>
								<td>{errors.alert.row.SERVER}</td>
								<td>{errors.alert.row.SERVICE}</td>
								<td>{errors.alert.row.PARAM}</td>
								<td>{errors.alert.row.VAL}</td>
							</tr>
							<!-- END row -->
						</table>
					</div>
					<!-- END alert -->
				</td>
			</tr>
			<!-- END errors -->

			<!-- BEGIN warnings -->
			<tr>
				<td colspan="3" style='text-align:center; background-color:#FFFFaa;'>
					<br />Я недоумеваю<br /><br />
				</td>
			</tr>
			<!-- END warnings -->
		</table>
	</div>
	<!-- BEGIN server -->
	<div id="server" style="padding-left:30px;padding-top:20px;">
		<table width="100%" class="tbl">
			<tr>
				<th Title="Создан [{server.CREATED}] {server.COMPUTER_NAME}" align="left">{server.NAME}</th>
				<!-- BEGIN diag -->
				<th style="color:gray;font-weight: normal;" align="right">
					(Diag version: [{server.diag.VER}])
				</th>
				<!-- END diag -->
				<th width="30px" title="Обновлено"><img src="/pic/ico/reboot_18x18.png" width="16" height="16" title="Обновлено" /></th>
			</tr>
		</table>
		<div id="server_prop" style="padding-left:30px;">
			<table width="100%">
				<tr>
					<td title="Память: Занято: [{server.MEM_USED}]; свободно [{server.MEM_FREE}], всего [{server.MEM_TOTAL}]" v-aligin="middle">
						<img src="/pic/ico/chip_20x20.png" width="16" height="16" /> 
						{server.MEM_USED} / {server.MEM_TOTAL} <span style="{server.S_MEM_FREE}">({server.MEM_FREE} free) </span>
					</td>
					<td width="30px" align="right" Title="Обновлено {server.MEM_UPDATED_TAGO} назад ({server.MEM_UPDATED})">{server.MEM_UPDATED_TAGO}</td>
				</tr>
				<tr>
					<td title="IP: From external network: [{server.IP_METRO}]; Local Server IP: [{server.IP}]; Not used netcard: [{server.IP_USELESS}]">
						<img src="/pic/ico/network_24x24.png" width="16" height="16" /> 
						{server.IP_SPECIAL}Local: {server.IP}<span style="color:#c0c0c0;">{server.IP_USELESS}</span>
					</td>
					<td width="30px" align="right" Title="Обновлено хз когда"></td>
				</tr>
			</table>
			<!-- BEGIN hdd -->
			<table width="100%">
				<tr>
					<td style="" title="HDD: [{server.hdd.LETTER}]; Свободно: [{server.hdd.FREE}]; Всего: [{server.hdd.SPACE}]">
						<img src="/pic/ico/hdd_green_22x22.png" width="16" height="16" /> 
						{server.hdd.LETTER}: <span style="{server.hdd.S_FREE}"> {server.hdd.FREE}</span> / {server.hdd.SPACE}
					</td>
					<td style="" width="30px" align="right" title="Обновлено {server.hdd.MODIFIED}">{server.hdd.MODIFIED_TAGO}</td>
				<!--{server.hdd.RECEIVED} {server.hdd.RECEIVED_TAGO} -->
				</tr>
			</table>
			<!-- END hdd -->
			<table width="100%">
				<tr>
					<th align="left" colspan="2">Сервисы</th>
					<th>Ver</th>
					<th><img src="/pic/ico/queue_20x20.png" width="16" height="16" title="Queue" /> Queue</th>
					<th title="Logs">Log</th>
					<th title="Эксепшены (ошибки)"><img src="/pic/ico/attention_2_16x16.png" width="16" height="16" /></th>
					<th title="Занимаемые ресурсы">
						<img src="/pic/ico/chip_20x20.png" width="16" height="16" />
						<!--img src="/pic/ico/graph_22x22.png" width="16" height="16" /-->
					</th>
					<th title="Threads"><img src="/pic/ico/threading_22x22.png" width="16" height="16" /></th>
					<th title="Messages"><img src="/pic/ico/messages_20x20.png" width="16" height="16" /></th>
					<th title="Frames"><img src="/pic/ico/binary_20x20.png" width="14" height="16" /></th>
					<th title="Frames RAW"><img src="/pic/ico/binary_20x20.png" width="14" height="16" /></th>
					<th width="30px" title="Обновлено"><img src="/pic/ico/reboot_18x18.png" width="16" height="16" title="Обновлено" /></th>
				</tr>
			<!-- BEGIN srvc -->
				<tr style="{server.srvc.S_START_TYPE}">
					<td width="10px">&nbsp;
						<div name="{server.NAME}_{server.srvc.NAME}_srvcProp" id="{server.NAME}_{server.srvc.NAME}_srvcProp" style="position:absolute;display:none;margin-left:-10px;background-color:white;padding:10px;border:5px;box-shadow: -1px -1px 5px 5px rgba(0, 0, 0, .2);color:black;font-weight:normal;">
							<p align="right"><a style="cursor:pointer;font-weight:bold;align:right;" onclick="ShowHideDiv('{server.srvc.NAME}_srvcProp');">[ CLOSE ]</a></p>
							<br>
							<h3>By block reports: STB22094</h3>
							<table cellspacing="1px" cellpadding="1px" class="tbl" width="100%">
								<tr>
									<th width="200px">Name</th>
									<th>Value</th>
								</tr>
								<tr><td>Обновлено:</td><td>{server.srvc.MODIFIED_TAGO} ({server.srvc.MODIFIED})</td></tr>
								<tr><td>Алиас</td><td>{server.srvc.ALIAS}</td></tr>
								<tr><td>Сервис</td><td>{server.srvc.NAME} ({server.srvc.PLATFORM})</td></tr>
								<tr><td>Название</td><td>{server.srvc.DISPLAY_NAME}</td></tr>
								<tr><td>Путь</td><td>{server.srvc.FILE_PATH}</td></tr>
								<tr><td>Файл изменен</td><td>{server.srvc.FILE_MODIFIED} ({server.srvc.FILE_MODIFIED_TAGO})</td></tr>
								<tr><td>Версия</td><td>{server.srvc.VER}</td></tr>
								<tr><td>Создано</td><td>{server.srvc.CREATED_TAGO} ({server.srvc.CREATED})</td></tr>
								<tr><td>Статус (Тип зауска)</td>
									<td style="{server.srvc.S_STATE} {server.srvc.S_START_TYPE}">
										{server.srvc.STATE_IMG} {server.srvc.STATE} / {server.srvc.START_TYPE} {server.srvc.UNINSTALLED}
									</td>
								</tr>
								<tr>
									<td>Лог файл</td>
									<td style="{server.srvc.S_LOG}">
										Последняя запись: {server.srvc.LOG_LAST_TAGO} ({server.srvc.LOG_LAST});<br />
										Изменен: {server.srvc.LOG_CHANGED_TAGO} назад ({server.srvc.LOG_CHANGED});<br />
										Файл: {server.srvc.LOG_FILE};<br />
										Размер: {server.srvc.LOG_SIZE} / {server.srvc.LOG_SIZE_SRVC}
									</td>
								</tr>
								<tr><td>Очередь</td>
									<td>
										{server.srvc.QUEUE_TITLE}{server.srvc.QUEUE_TITLE_UP_BR}
										{server.srvc.QUEUE_TITLE_UP}{server.srvc.QUEUE_TITLE_DOWN_BR}
										{server.srvc.QUEUE_TITLE_DOWN}
										{server.srvc.QUEUE_REFRESH}
										<!--{server.srvc.QUEUE_FILE} / {server.srvc.QUEUE_SIZE}; изменен: {server.srvc.QUEUE_MODIFIED_TAGO} ({server.srvc.QUEUE_MODIFIED})-->
									</td>
								</tr>
								<tr><td>Ошибки</td>
									<td title="Последняя: {server.srvc.LAST_ERR_TAGO} назад ({server.srvc.LAST_ERR}), {server.srvc.ERROR_SIZE}, файл: [{server.srvc.ERROR_FILE}]">
									Последняя: {server.srvc.LAST_ERR_TAGO} назад, {server.srvc.ERROR_SIZE}, файл: [{server.srvc.ERROR_FILE}]
									</td>
								</tr>
								<tr><td>ROOT_HUB</td><td>{server.srvc.ROOT_HUB}</td></tr>
								<tr><td>Фреймов</td>
									<td>
										{server.srvc.FRAMES} шт, обновлено {server.srvc.FRAMES_TAGO} назад ({server.srvc.FRAMES_DATE})
										<br />FramesCount: {server.srvc.FRAMES_COUNT}; FramesRow: {server.srvc.FRAMES_ROW}
									</td>
								</tr>
								<tr><td>Месседжей</td><td>{server.srvc.MSG} шт, обновлено {server.srvc.MSG_TAGO} назад ({server.srvc.MSG_DATE})</td></tr>
								<tr><td>Ресурсы</td>
									<td>
									Mem Private: {server.srvc.MEM_PVT}; обновлено {server.srvc.MEM_PVT_TAGO} назад ({server.srvc.MEM_PVT_DATE}) <br />
									Mem WorkingSet: {server.srvc.MEM_WKS}; обновлено {server.srvc.MEM_WKS_TAGO} назад ({server.srvc.MEM_WKS_DATE})<br />
									Threads: {server.srvc.THREAD}; обновлено {server.srvc.THREAD_TAGO} назад ({server.srvc.THREAD_DATE})
									</td>
								</tr>
								<tr><td></td><td></td></tr>
							</table>
							<div id="STB22094_blockhistory">...</div>
						</div>
					</td>
					<td onclick="ShowHideDiv('{server.NAME}_{server.srvc.NAME}_srvcProp');" style="cursor:pointer; {server.srvc.S_STATE} {server.srvc.S_START_TYPE}" 
						title="Сервис: {server.srvc.NAME}; RootHub: [{server.srvc.ROOT_HUB}]; Состояние: {server.srvc.STATE} / {server.srvc.START_TYPE}; {server.srvc.UNINSTALLED}">
						{server.srvc.DISPLAY_NAME} {server.srvc.UNINSTALLED}{server.srvc.TEST}
					</td>
					<td align="right" style="{server.srvc.S_FILE_VERSION}" Title="{server.srvc.LAST_VERSION}">{server.srvc.VER}</td>
					<td align="center" style="{server.srvc.S_QUEUE_WRN}{server.srvc.S_QUEUE_ERR}" title="{server.srvc.QUEUE_TITLE_FULL}">
						<!--{server.srvc.QUEUE}-->
						<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=ServerStarte_Hist_Measure&prms=Instance={server.srvc.I_GUID};Service={server.srvc.NAME};Measure=Queue" 
							onclick="return hs.htmlExpand(this, { slideshowGroup: 'service-measure-Queue-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
							title="">
							{server.srvc.QUEUE_STR}
						</a>
						{server.srvc.QUEUE_REFRESH}
					</td>
					<td align="center" style="{server.srvc.S_LOG_WRN}{server.srvc.S_LOG_ERR}" title="Последняя запись {server.srvc.LOG_LAST_TAGO} назад ({server.srvc.LOG_LAST}); файл изменен: {server.srvc.LOG_CHANGED_TAGO} назад ({server.srvc.LOG_CHANGED}), файл: {server.srvc.LOG_FILE} ({server.srvc.LOG_SIZE} / {server.srvc.LOG_SIZE_SRVC})">
						{server.srvc.LOG_LAST_TAGO}
						<!--{server.srvc.LOG}-->
					</td>
					<td align="center" style="{server.srvc.S_LAST_ERR}">
						<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=ServerStarte_Hist_Errors&prms=Instance={server.srvc.I_GUID};Service={server.srvc.NAME}" 
							onclick="return hs.htmlExpand(this, { slideshowGroup: 'service-errors-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
							title="">
							{server.srvc.LAST_ERR_TAGO}
						</a>
					</td>
					<td align="center" style="{server.srvc.S_MEM_WRN}{server.srvc.S_MEM_ERR}" 
						title="Mem Private: [{server.srvc.MEM_PVT}]; обновлено {server.srvc.MEM_PVT_TAGO} назад ({server.srvc.MEM_PVT_DATE}); Mem WorkingSet: [{server.srvc.MEM_WKS}]; обновлено {server.srvc.MEM_WKS_TAGO} назад ({server.srvc.MEM_WKS_DATE})">
						<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=ServerStarte_Hist_Measure&prms=Instance={server.srvc.I_GUID};Service={server.srvc.NAME};Measure=Memory" 
							onclick="return hs.htmlExpand(this, { slideshowGroup: 'service-measure-Memory-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
							title="">
							{server.srvc.MEM}
						</a>
					</td>
					<td align="center" style="{server.srvc.S_THREADS_WRN}{server.srvc.S_THREADS_ERR}" Title="Threads: {server.srvc.THREAD}; обновлено {server.srvc.THREAD_TAGO} назад ({server.srvc.THREAD_DATE})">
						<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=ServerStarte_Hist_Measure&prms=Instance={server.srvc.I_GUID};Service={server.srvc.NAME};Measure=Threads" 
							onclick="return hs.htmlExpand(this, { slideshowGroup: 'service-measure-threads-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
							title="">
							{server.srvc.THREAD}
						</a>
					</td>
					<td align="center" style="{server.srvc.S_MSG_WRN}{server.srvc.S_MSG_ERR}" title="Месседжей: [{server.srvc.MSG}] шт, обновлено {server.srvc.MSG_TAGO} назад ({server.srvc.MSG_DATE})">
						<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=ServerStarte_Hist_Measure&prms=Instance={server.srvc.I_GUID};Service={server.srvc.NAME};Measure=Messages" 
							onclick="return hs.htmlExpand(this, { slideshowGroup: 'service-measure-messages-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
							title="">
							{server.srvc.MSG}
						</a>
					</td>
					<td align="center" style="{server.srvc.S_FRAMES_WRN}{server.srvc.S_FRAMES_ERR}" title="Фреймов: [{server.srvc.FRAMES}] шт, обновлено {server.srvc.FRAMES_TAGO} назад ({server.srvc.FRAMES_DATE})">
						<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=ServerStarte_Hist_Measure&prms=Instance={server.srvc.I_GUID};Service={server.srvc.NAME};Measure=Frames" 
							onclick="return hs.htmlExpand(this, { slideshowGroup: 'service-measure-frames-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
							title="">
							{server.srvc.FRAMES}
						</a>
					</td>
					<td align="center" style="{server.srvc.S_FRAMES_RAW_WRN}{server.srvc.S_FRAMES_RAW_ERR}" title="Фреймов: [{server.srvc.FRAMES_RAW}] шт, обновлено {server.srvc.FRAMES_RAW_TAGO} назад ({server.srvc.FRAMES_RAW_DATE})">
						<a href="/dynamic/proxy.php?rnd={RANDOM}&srv={SERVER_NAME}&page=ServerStarte_Hist_Measure&prms=Instance={server.srvc.I_GUID};Service={server.srvc.NAME};Measure=Frames_RAW" 
							onclick="return hs.htmlExpand(this, { slideshowGroup: 'service-measure-frames_raw-group', objectType: 'ajax', contentId: 'highslide-html-8' } )"
							title="">
							{server.srvc.FRAMES_RAW}
						</a>
					</td>
					<td align="right" style="{server.srvc.S_MODIFIED_WRN}" title="Обновлено {server.srvc.MODIFIED}">{server.srvc.MODIFIED_TAGO}</td>
				</tr>
			<!-- BEGIN clients -->
				<tr>
					<td colspan="11" style="padding:0px;cursor:pointer;padding-left:30px;" OnClick="ShowHideDiv('ServiceClients_{CLIENT_SRV}-{server.COMPUTER_NAME}-{server.srvc.NAME}')" Title="Список клиентов">
						<span style="font-weight:bold;">Клиенты Сервиса ==&gt;</span>
						<div id="ServiceClients_{CLIENT_SRV}-{server.COMPUTER_NAME}-{server.srvc.NAME}" style="display:none;">
						<table width="100%" border="0" class="tbl" style="border-spacing: 1px;"><!-- style="border-spacing: 1px;" -->
							<tr>
								<th align="left">Клиент</th>
								<th width="150px" align="left">Роль</th>
								<th width="250px" align="left">Файл очереди</th>
								<th width="20px" title="Очередь"><img src="/pic/ico/queue_20x20.png" width="16" height="16" title="Queue" /></th>
								<th width="20px" title="Размер файла"><img src="/pic/ico/font_size_16x16.png" width="16" height="16" /></th>
								<!--th width="50px">&nbsp;</th-->
								<th width="30px" title="Обновлено"><img src="/pic/ico/reboot_18x18.png" width="16" height="16" title="Обновлено" /></th>
							</tr>
							<!-- BEGIN row -->
							<tr>
								<td>{server.srvc.clients.row.NAME}</td>
								<td>{server.srvc.clients.row.ROLE}</td>
								<td>{server.srvc.clients.row.QUEUE_FILE_NAME}</td>
								<td align="center">{server.srvc.clients.row.USE_QUEUE}</td>
								<td align="right" style="{server.srvc.clients.row.S_SIZE_GRAY}{server.srvc.clients.row.S_SIZE_ERR}">{server.srvc.clients.row.QUEUE_FILE_SIZE}</td>
								<!--td></td-->
								<td align="right" style="{server.srvc.clients.row.S_UPD_GRAY}" title="Очередь обновлена {server.srvc.clients.row.QUEUE_TAGO} назад ({server.srvc.clients.row.QUEUE_DATE})">{server.srvc.clients.row.QUEUE_TAGO}</td>
							</tr>
							<!-- END row -->
						</table>
						</div>
					</td>
				</tr>
			<!-- END clients -->
			<!-- END srvc -->
			</table>
		</div>
	</div>
	<!-- END server -->
</div>
<hr />

{ARTICLE}
{TEMP}

<!-- BEGIN legend -->
<h3>Легенда</h3>
<ul>
<li>Красным подсвечивает ячейки значения в которых выходят за определенный лимит (настраивается в БД).</li>
<li>В группе "*.Errors files (last 7 days)" - не подсвечивает ничего. только статистическая информация.</li>
<li>при клике в значение ячейки - просмотр истории. С подсветкой выходящих за лимиты.</li>
</ul>
<!-- END legend -->

