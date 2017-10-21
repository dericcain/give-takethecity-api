<style>
    table.table {
        width: 100%;
        text-align: left;
        border-collapse: collapse;
    }

    table.table td, table.table th {
        border: 1px solid #AAAAAA;
        padding: 3px 2px;
    }

    table.table tbody td {
        font-size: 14px;
        padding: 8px;
    }

    table.table tr:nth-child(even) {
        background: #F0F0F0;
    }
</style>
<p>A donation has been submitted.</p>
<br>
<table class="table">
    <tbody>
    <tr>
        <td width="120">Amount</td>
        <td>${{ number_format($donation->amount / 100, 2) }}</td>
    </tr>
    <tr>
        <td width="120">Name</td>
        <td>{{ $donation->donor->first_name }} {{ $donation->donor->last_name }}</td>
    </tr>
    <tr>
        <td width="120">Designation</td>
        <td>{{ $donation->designation->name }}</td>
    </tr>
    <tr>
        <td width="120">Email</td>
        <td>{{ $donation->donor->email }}</td>
    </tr>
    <tr>
        <td width="120">Phone</td>
        <td>{{ $donation->donor->phone }}</td>
    </tr>
    <tr>
        <td width="120">Address</td>
        <td>{{ $donation->donor->address }} {{ $donation->donor->zip }}</td>
    </tr>
    <tr>
        <td width="120">Is recurring</td>
        <td>{{ $donation->is_recurring == 1 ? 'Yes' : 'No' }}</td>
    </tr>
    <tr>
        <td width="120">Is paying fees</td>
        <td>{{ $donation->is_paying_fees == 1 ? 'Yes' : 'No' }}</td>
    </tr>
    <tr>
        <td width="120">Date</td>
        <td>{{ $donation->created_at->format('F j, Y') }}</td>
    </tr>
    @if($donation->general_comments)
        <tr>
            <td width="120">General Comments</td>
            <td>{{ $donation->general_comments }}</td>
        </tr>
    @elseif($donation->mission_support)
        <tr>
            <td width="120">Mission Support</td>
            <td>{{ $donation->mission_support }}</td>
        </tr>
    @elseif($donation->staff_support)
        <tr>
            <td width="120">Staff support</td>
            <td>{{ $donation->staff_support }}</td>
        </tr>
    @endif
    </tbody>
</table>
