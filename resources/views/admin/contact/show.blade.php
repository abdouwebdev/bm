<table>
    <tr>
        <th>Account Status</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>
            @if ($contact->active === 1)
                <span class="badge badge-success">
                    ACTIVE
                </span>
            @else
                <span class="badge badge-danger">
                    NOT ACTIVE
                </span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Name</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>{{ $contact->name }}</td>
    </tr>
    <tr>
        <th>Email</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>{{ $contact->email }}</td>
    </tr>
    <tr>
        <th>Telephone</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>{{ $contact->phone }}</td>
    </tr>
    <tr>
        <th>Type</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>
            {{ $contact->customer ? 'Customer' . ($contact->supplier == true || $contact->employee == true ? ', ' : '') : '' }}
            {{ $contact->supplier ? 'Supplier' . ($contact->customer == true || $contact->employee == true ? ', ' : '') : '' }}
            {{ $contact->employee ? 'Employee' . ($contact->customer == true || $contact->supplier == true ? ', ' : '') : '' }}
        </td>
    </tr>
    <tr>
        <th>Address</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>{{ $contact->address }}</td>
    </tr>
    <tr>
        <th>City</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>{{ $contact->city }}</td>
    </tr>
    <tr>
        <th>Code Postal</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>{{ $contact->code_post }}</td>
    </tr>
    <tr>
        <th>Contact Code</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>{{ $contact->code_contact }}</td>
    </tr>
    <tr>
        <th>Currency</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>{{ $contact->currency }}</td>
    </tr>
    <tr>
        <th>NIC</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>{{ $contact->nik }}</td>
    </tr>
    <tr>
        <th>Person Contact</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>{{ $contact->person_contact }}</td>
    </tr>
    <tr>
        <th>Website</th>
        <th>:</th>
        <td></td>
        <td></td>
        <td>{{ $contact->website }}</td>
    </tr>
</table>
