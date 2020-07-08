<table width="300px">
	<!-- BEGIN row -->
	<tr>
		<td>{row.NAME}</td>
		<td style="{row.BLOCK_LAST_CONN_STYLE}">
			<img src='/pic/ico/db_16x16.png' width='16' height='16' alt='Database'> : {row.BLOCK_LAST_CONN}
		</td>
		<td style="{row.DRIVERS_STYLE}">
			<img src='/pic/ico/person_16x16.png' width='16' height='16' alt='Drivers'> : {row.DRIVERS}
		</td>
		<td style="{row.BLOCKS_STYLE}">
			<img src='/pic/ico/bus_16x16.png' width='16' height='16' alt='Wagons'> : {row.BLOCKS} / {row.BLOCKS_TOTAL}
		</td>
	</tr>
	<!-- END row -->
</table>
