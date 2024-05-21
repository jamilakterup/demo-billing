@foreach($data as $product)
<tr class="row_{{$product->id}}">
    <td>{{$loop->iteration}}</td>
    <td>
        {{$product->name}}
    </td>
    <td>
        {{$product->attributes['unit_name']}}
    </td>
    <td align="right">
        {{$product->price}}
    </td>
    <td align="right">
        {{$product->quantity}}
    </td>
    <td align="right">
    {{$product->price*$product->quantity}}
    </td>
    <td align="center"><a href="#" class="btn btn-sm btn-danger cart-delete" cartid="{{$product->id}}"><i class="fa fa-trash-alt"></i></a></td>
</tr>
@endforeach
