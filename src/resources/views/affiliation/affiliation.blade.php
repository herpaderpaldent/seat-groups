<h3>Available for whom</h3>
<p>Select the affiliation to which the SeAT Group is bound. This means any main_character (and its user group) which is
  not part of a selected affiliation will not be part of this SeAT Group.</p>

<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#corporation-filter" data-toggle="tab">Corporation Filter</a></li>
    <li><a href="#title-filter" data-toggle="tab">Corporation Title Filter</a></li>
  </ul>
  <div class="tab-content">

    <div class="tab-pane active" id="corporation-filter">
      @include('seatgroups::affiliation.partials.corporation-form')
    </div>
    <!-- /.tab-pane -->
    <div class="tab-pane" id="title-filter">
      @include('seatgroups::affiliation.partials.corporation-title-form')
    </div>

    @include('seatgroups::affiliation.partials.current-affiliations')
    <!-- /.tab-pane -->
  </div>
  <!-- /.tab-content -->
</div>