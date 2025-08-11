@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Attendance for {{ $employee->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#attendanceModal" onclick="setAttendanceType('clock_in')" @if($attendanceToday && $attendanceToday->clock_in) disabled @endif>
            Clock In
        </button>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#attendanceModal" onclick="setAttendanceType('clock_out')" @if(!$attendanceToday || $attendanceToday->clock_out) disabled @endif>
            Clock Out
        </button>
    </div>

    @if($attendanceToday)
    <div class="mb-3">
        <p><strong>Clock In:</strong> {{ $attendanceToday->clock_in ?? 'Not clocked in' }}</p>
        @if($attendanceToday->clock_in_photo)
            <img src="{{ asset('storage/' . $attendanceToday->clock_in_photo) }}" width="150" alt="Clock In Photo">
        @endif
        <p><strong>Clock Out:</strong> {{ $attendanceToday->clock_out ?? 'Not clocked out' }}</p>
        @if($attendanceToday->clock_out_photo)
            <img src="{{ asset('storage/' . $attendanceToday->clock_out_photo) }}" width="150" alt="Clock Out Photo">
        @endif
        <p><strong>Total Hours:</strong> {{ $attendanceToday->total_hours ?? '-' }}</p>
    </div>
    @endif
</div>

<!-- Attendance Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="attendanceForm" method="POST" enctype="multipart/form-data" action="{{ route('attendance.store', $employee->id) }}">
      @csrf
      <input type="hidden" name="attendance_type" id="attendance_type" value="">
      <input type="hidden" name="photo" id="photo_input" required>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Clock In/Out with Photo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <video id="video" width="320" height="240" autoplay></video>
          <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
          <div class="mt-3">
            <button type="button" id="snapBtn" class="btn btn-primary">Take Photo</button>
          </div>
          <div class="mt-3">
            <img id="photoPreview" src="" alt="Photo Preview" style="display:none; max-width: 100%;">
          </div>
          <p><small>You must take a photo to submit attendance.</small></p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" id="submitBtn" disabled>Submit Attendance</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');
  const snapBtn = document.getElementById('snapBtn');
  const photoInput = document.getElementById('photo_input');
  const photoPreview = document.getElementById('photoPreview');
  const submitBtn = document.getElementById('submitBtn');

  // Start camera when modal opens
  let stream;
  const attendanceModal = document.getElementById('attendanceModal');
  attendanceModal.addEventListener('show.bs.modal', function () {
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(s => {
        stream = s;
        video.srcObject = stream;
        submitBtn.disabled = true;
        photoPreview.style.display = 'none';
        photoPreview.src = '';
        photoInput.value = '';
      })
      .catch(() => alert('Cannot access camera'));
  });

  // Stop camera when modal closes
  attendanceModal.addEventListener('hidden.bs.modal', function () {
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
    }
  });

  snapBtn.addEventListener('click', function () {
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    const imageData = canvas.toDataURL('image/png');
    photoPreview.src = imageData;
    photoPreview.style.display = 'block';

    photoInput.value = imageData.split(',')[1];
    submitBtn.disabled = false;
  });
});

function setAttendanceType(type) {
  document.getElementById('attendance_type').value = type;
}
</script>
@endsection
