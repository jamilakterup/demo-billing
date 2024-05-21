<div>
    <div class="text-right py-1 px-3">
        <a class="btn btn-sm btn-primary" href="{{route('project.send.email',$showProject->id)}}"><i
                class="fas fa-paper-plane"></i> Send Email</a>
    </div>

    <iframe src="{{asset('/storage/'.$showProject->file)}}" style="overflow: auto; width: 100%; min-height: 500px;">
</div>