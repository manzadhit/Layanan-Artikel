<!-- Report Form -->
<form class="dropdown-item" action="{{ route('report.store') }}" method="POST">
    @csrf
    <input type="hidden" name="reportable_id" value="{{ $reportable->id }}">
    <input type="hidden" name="reportable_type" value="{{ get_class($reportable) }}">
    <div class="form-group">
        <label for="reason">Alasan Laporan:</label>
        <select name="reason" id="reason" class="form-control" required>
            <option value="">Pilih Alasan</option>
            <option value="spam">Spam</option>
            <option value="harassment">Pelecehan</option>
            <option value="inappropriate">Konten Tidak Pantas</option>
            <option value="other">Lainnya</option>
        </select>
    </div>
    <button type="submit" class="btn btn-danger mt-2">
        @php
            $reportableType = strtolower(class_basename($reportable));
            $reportableText = ucfirst($reportableType);
            echo "Report " . $reportableText;
        @endphp
    </button>
</form>