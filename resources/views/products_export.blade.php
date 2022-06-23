<table>
    <thead>
    <tr>
        <th>key</th>
        <th>name</th>
        <th>unit</th>
        <th>cost</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{$product->key}}</td>
            <td>{{$product->name}}</td>
            <td>{{$product->unit}}</td>
            <td>{{$product->cost}}</td>
        </tr>
    @endforeach
    </tbody>
</table>