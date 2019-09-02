{{ Form::open(array('url' => '/settings/info', 'method' => 'POST')) }}
<div class="pt-1">
    <div class="form-group row">
        <label for="example-text-input" class="col-md-2 col-form-label">My Intro.</label>
        <div class="col-md-10">
            <textarea class="form-control" name="intro" rows="3">{{$intro}}</textarea>
        </div>
    </div>

    <div class="form-group row">
        <label for="example-text-input" class="col-md-2 col-form-label">Location</label>
        <div class="col-md-10">
            <input class="form-control" type="text" name="mylocation" value="{{$location}}" >
        </div>
    </div>

    <div class="form-group row">
        <label for="example-text-input" class="col-md-2 col-form-label">Relationship</label>
        <div class="col-md-10">
            <select class="form-control" id="exampleSelect1" name="relationship">
                <?php
                $relation[0] = "Select";
                $relation[1] = "Single";
                $relation[2] = "Committed";
                $relation[3] = "Complicated";
//                $relation[4] = "Engaged";


                ?>
                @for($i=0;$i<4;$i++)
                    @if($i == $relationship)
                        <option value="{{$i}}" selected>{{$relation[$i]}}</option>
                    @else
                        <option value="{{$i}}">{{$relation[$i]}}</option>
                    @endif

                @endfor


            </select>
        </div>
    </div>

    <div class="col-xs-12 text-xs-right pt-2 pb-1 " style="position:absolute; bottom:0; right:0;">
        <div class="offset-md-9 col-md-3 px-0">

            <button type="submit" class="btn btn-primary btn-block px-2">Save</button>
        </div>
    </div>
</div>
{{ Form::close() }}