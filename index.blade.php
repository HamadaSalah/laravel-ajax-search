@extends('admin.master')
@section('content')
@push('javascript')
    <script type="text/javascript">
      var lastdata = $('tbody').html();
      var edit = document.getElementById('searchuser').getAttribute("edit"); 
      var del = document.getElementById('searchuser').getAttribute("del"); 
      $('body').on('keyup','#searchuser', function() {
        var mydata = '';
        var mysearch = $(this).val();
        $.ajax({
          method: "POST",
          url: '{{route("admin.adminsearch")}}',
          dataType: 'JSON',
          data : {
            '_token' : '{{csrf_token()}}',
            mysearch : mysearch
          },
          success : function(res){
            if (res.length>0) {
              for (var count = 0; count < res.length; count++) {
                mydata+= '<tr><th scope="row">';
                mydata+=count+1;
                mydata+='</th><td>';
                mydata+=res[count].name;
                mydata+='</td><td>';
                mydata+=res[count].email;
                mydata+='<td><button class="btn btn-success">';
                mydata+=res[count].level;
                mydata+='</button></td>';  
                mydata+='<td><a data-fancybox="gallery" href="{{ URL::asset('/storage/images/users/') }}'+'/'+res[count].image+'"><img style="max-height: 80px" class="img-thumbnail" src="{{ URL::asset('/storage/images/users/') }}'+'/'+res[count].image+'"></a></td>' ; 
                mydata+='<td><a href="'+window.location.href+'/'+res[count].id+'/edit'+'"><button type="submit" class="btn btn-primary"/>'+edit+'</button></a>';
                  mydata+=' <a href="'+window.location.href+'/'+res[count].id+'/edit'+'"><button type="submit" class="btn btn-danger"/>'+del+'</button></a>';
              }
            }
            else {
              mydata+= '<tr><th scope="row" colspan="6" style="text-align:center">NO User To Show</th><tr/>';
            }
              if (mysearch !='') {
                $('tbody').html(mydata);
                }
                else {
                  $('tbody').html(lastdata);
                }
          }

        });
      });
    </script>
@endpush

<h1 style="text-align: center">@lang('site.all_admins')</h1>
<div class="form-group">
<input type="text" class="form-control" id="searchuser" placeholder="Search Your Users Here (Live Search...)" edit="@lang('site.edit')" del="@lang('site.delete')">
</div>

<table class="table table-striped">
    <thead style="background: rgb(28 219 5 / 60%);">
      <tr>
        <th scope="col">#</th>
        <th scope="col">@lang('site.name')</th>
        <th scope="col">@lang('site.email')</th>
        <th scope="col">@lang('site.role')</th>
        <th scope="col">@lang('site.image')</th>
        <th scope="col">@lang('site.action')</th>
      </tr>
    </thead>
    <tbody id="all_data">
        @foreach ($admins as $key => $admin)
        <tr>
            <th scope="row">{{$key+1}}</th>
            <td>{{$admin->name}}</td>
            <td>{{$admin->email}}</td>
            <td><button class="btn btn-success">{{$admin->level}}</button></td>
            <td>
              <a data-fancybox="gallery" href="{{asset('storage/images/users/'.$admin->image)}}"><img style="max-height: 80px" class="img-thumbnail" src="{{asset('storage/images/users/'.$admin->image)}}"></a>
            </td>
            <td>
              <a href="{{ route('admin.edit', $admin->id )}}"><button type="submit" class="btn btn-primary"/>@lang('site.edit')</button></a>
              <form style="display: inline-block" action="{{ route('admin.destroy', $admin->id )}}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger"/>@lang('site.delete')</button>
            </form>
            </td>
          </tr>
    
        @endforeach
    </tbody>
  </table>
@endsection