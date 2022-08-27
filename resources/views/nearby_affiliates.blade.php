<style>
table td{
	border: 1px solid black;
}
</style>

<table>
	<thead>

	</thead>
	<tbody>
		@foreach($invitation_table as $invitation)
			<tr>
				<td>{{ $invitation['id'] }}</td>
				<td>{{ $invitation['name'] }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
