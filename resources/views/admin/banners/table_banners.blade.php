<h4>
    banners</h4>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead class="">
            <tr>
                {{-- <th scope="col">#</th> --}}
                <th scope="col">Image</th>
                <th scope="col">Redirect URL</th>
            </tr>
        </thead>
        <tbody class="">
            <a href="/admin/banners/create">
                <button class="px-3 mb-3 btn btn-warning rounded-0 float-end">
                    <i class="bi bi-plus-lg"></i>
                    <span class="ms-2">Add banners</span>
                </button>
            </a>
            @foreach ($items as $item)
                <tr role="button">
                    <td class="py-2" onclick=redirectTo("/admin/banners/edit/{{ $item->id }}")>
                        <img class="rounded" src="{{ asset($item->imageUrl) }}" width="50px" alt="">
                    </td>
                    <td class="py-2" onclick=redirectTo("/admin/banners/edit/{{ $item->id }}")>
                        {{ $item->redirectUrl }}</td>
                    <td class="py-2">
                        <a href="/admin/banners/delete/{{ $item->id }}"
                            class="btn btn-danger btn-sm rounded-0">Delete</a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    {{ $items->links() }}
</div>
