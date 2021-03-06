<aside class="main-sidebar">
    <section class="sidebar">
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{ trans('dashboard::phrases.Search') }}..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        <ul class="sidebar-menu">
            @foreach(app('dashboard')->getMenuBuilder() as $menuItem)
                <li class="{{$menuItem->isActive() ? 'active' : ''}}">
                    <a href="{{$menuItem->getLink()}}">
                        <i class="fa {{$menuItem->getIcon()}}"></i> <span>{{$menuItem->getName()}}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
</aside>