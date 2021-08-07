<?php
$attribute = $addAttribute;
foreach ($fieldExtra as $extraKey => $extraVal) {
    $attribute[$extraKey] = $extraVal;
}
$attribute['id'] = $fieldName;
if ($fieldRequired == 1) {
    $attribute['required'] = 'true';
}
?>
<div class="form-group">
    <label for="{{$fieldName}}">{{ __($fieldLang) }} {{ $fieldRequired == 1 ? ' *' : '' }}</label>
    @if($fieldValue)
        <?php
        $getFile = json_decode($fieldValue, true);
        ?>
        @if ($getFile)
        @foreach($getFile as $file)
        <br/>
        <a href="{{ asset($file['location']) }}" target="_blank" title="{{$file['name']}}" download>{{ $file['name'] }} </a>
        <br/>
        @endforeach
        @endif
    @endif
    @if(!in_array($viewType, ['show']))
        <br/>
        {{ Form::file($fieldName, $attribute) }}
        <br/>
    @endif
    @if(isset($fieldMessage)) <span class="small">{{ $fieldMessage }}</span> @endif
    @if($errors->has($fieldName)) <div class="form-control is-invalid" style="display: none;"></div><div class="invalid-feedback">{{ $errors->first($fieldName) }}</div> @endif
</div>
