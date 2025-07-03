<h4>
    offers</h4>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead class="">
            <tr>
                {{-- <th scope="col">#</th> --}}
                <th scope="col">Image</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Discount Percentage</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Link</th>
                <th scope="col">Status</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody class="">
            <a href="/admin/offers/create">
                <button class="px-3 mb-3 btn btn-warning rounded-0 float-end">
                    <i class="bi bi-plus-lg"></i>
                    <span class="ms-2">Add offers</span>
                </button>
            </a>
            @foreach ($items as $item)
                <tr role="button">
                    <td class="py-2" onclick=redirectTo("/admin/offers/edit/{{ $item->id }}")>
                        <img class="rounded" src="{{ asset($item->image) }}" width="50px" alt="">
                    </td>
                    <td class="py-2" onclick=redirectTo("/admin/offers/edit/{{ $item->id }}")>
                        {{ $item->title }}</td>
                    <td class="py-2" onclick=redirectTo("/admin/offers/edit/{{ $item->id }}")>
                        {{ $item->description }}</td>
                    <td class="py-2" onclick=redirectTo("/admin/offers/edit/{{ $item->id }}")>
                        {{ $item->discount_percentage }}%</td>
                    <td class="py-2" onclick=redirectTo("/admin/offers/edit/{{ $item->id }}")>
                        {{ $item->start_date }}</td>
                    <td class="py-2" onclick=redirectTo("/admin/offers/edit/{{ $item->id }}")>
                        {{ $item->end_date }}</td>
                    <td class="py-2" onclick=redirectTo("/admin/offers/edit/{{ $item->id }}")>
                        <a href="{{ $item->link }}" target="_blank">{{ $item->link }}</a>
                    </td>
                    <td class="py-2" onclick=redirectTo("/admin/offers/edit/{{ $item->id }}")>
                        @if ($item->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="py-2">
                        <a href="/admin/offers/delete/{{ $item->id }}"
                            class="btn btn-danger btn-sm rounded-0">Delete</a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    {{ $items->links() }}
</div>
