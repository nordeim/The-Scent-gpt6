You can produce a first segment of roughly 10–15 MB by using ffmpeg’s **`-fs`** option. This option tells ffmpeg to stop writing the output file once it reaches a specified number of bytes. For example, to create a segment of up to 15 MB (15 × 1024 × 1024 ≈ 15,728,640 bytes), you can run:

```bash
ffmpeg -i hero.mp4 -c copy -fs 15728640 first_segment.mp4
```

**Explanation:**

- **`-i hero.mp4`** specifies your source video.
- **`-c copy`** copies the video and audio streams without re-encoding. Note that when copying streams, splitting occurs only at keyframe boundaries. This means the resulting file may not be *exactly* 15 MB but should be close.
- **`-fs 15728640`** limits the output file to roughly 15 MB (if you want about 10 MB, change this value to 10485760).
- **`first_segment.mp4`** is the output file containing the first segment.

This approach is useful if you only need the first portion of the video up to your size threshold. Keep in mind that the actual stop point may slightly vary because of keyframe restrictions when copying streams. If you need an even more precise split, you might have to re-encode with a constant bitrate, but that comes at the cost of additional processing and potential quality changes.  
https://copilot.microsoft.com/shares/EE9kGKn9VvFsRe5P2LVdc

---
Here's the rundown:

1. **Why your `-segment_size` option isn’t working:**  
   Your version of FFmpeg doesn’t recognize the `-segment_size` option. That option isn’t available (or is deprecated) in your build. Instead, FFmpeg provides the `-fs` flag to stop writing once the output file reaches a given size. However, note that using `-fs` with stream copying (via `-c copy`) will simply cut off the output file when the size limit is hit—it won’t automatically continue creating additional segments to cover the rest of the input.

2. **How to split the video into ~15 MB segments:**  
   Because FFmpeg doesn’t directly support “split and continue” based on file size, you generally have two alternatives:

   **Option A: Lossless splitting with a script using `-fs`**

   You can run a loop where you specify a start time (`-ss`) for the next segment and use `-fs 15728640` (15 MB in bytes) for each ffmpeg command. After each run, use `ffprobe` to determine the duration of the segment and update your start time. For example, create a Bash script like this:

   ```bash
   #!/bin/bash
   FILE="hero.mp4"
   BASENAME="hero"
   SEGMENT_SIZE=15728640  # 15MB in bytes

   # Get total duration (in seconds) of the input video
   DURATION=$(ffprobe -v error -show_entries format=duration \
       -of default=noprint_wrappers=1:nokey=1 "$FILE")
   CURR_TIME=0
   SEG_COUNT=1

   while (( $(echo "$CURR_TIME < $DURATION" | bc -l) )); do
       OUTPUT="${BASENAME}_part$(printf "%03d" $SEG_COUNT).mp4"
       echo "Creating segment $SEG_COUNT starting at $CURR_TIME seconds..."
       
       # Create segment starting at CURR_TIME and limited to SEGMENT_SIZE
       ffmpeg -ss "$CURR_TIME" -i "$FILE" -c copy -fs "$SEGMENT_SIZE" -avoid_negative_ts 1 "$OUTPUT"

       # Get duration of the created segment
       SEG_DURATION=$(ffprobe -v error -show_entries format=duration \
           -of default=noprint_wrappers=1:nokey=1 "$OUTPUT")
       echo "Segment duration: $SEG_DURATION seconds"

       # Update CURR_TIME for next segment
       CURR_TIME=$(echo "$CURR_TIME + $SEG_DURATION" | bc)
       SEG_COUNT=$((SEG_COUNT + 1))
   done
   ```

   **How It Works:**  
   - The script uses `-ss` to begin encoding from the current point in the video.
   - The `-fs 15728640` option tells FFmpeg to stop writing once the file reaches about 15 MB.
   - After creating each segment, the script uses `ffprobe` to get the segment’s duration and updates the start time so that the next segment picks up where the previous one ended.

   **Caveats:**  
   - Because the output is done with stream copy, splits can only occur at keyframes, so the file sizes might not be exactly 15 MB.  
   - If the file’s bitrate varies, the length (in seconds) of each segment may vary.

   **Option B: Re-encode with a Constant Bitrate**  
   If you re-encode the video using a constant bitrate, you can calculate a fixed duration that roughly corresponds to 15 MB. For example, if you choose a total bitrate (video + audio) of 1.5 Mbps, then:
   
   - 15 MB is 15 × 1024 × 1024 × 8 = 125829120 bits  
   - The segment duration in seconds ≈ 125829120 bits / 1500000 bits per second ≈ 84 seconds
   
   Then you can split using the segment muxer by time:
   
   ```bash
   ffmpeg -i hero.mp4 -c:v libx264 -b:v 1.2M -c:a aac -b:a 300k -f segment -segment_time 84 -reset_timestamps 1 output_%03d.mp4
   ```
   
   **Note:**  
   - This approach re-encodes the video (which takes longer and may affect quality).  
   - The chosen segment_time (84 seconds in this case) is an approximation; adjust your bitrate values if you need segments as close as possible to 15 MB.

---

**Summary:**  
- The `-segment_size` option isn’t valid in your FFmpeg version.  
- For lossless splitting, use a script that uses `-fs 15728640` with iterative `-ss` offsets.  
- For more predictable segment sizes, consider re-encoding with a constant bitrate and split by duration calculated from your bitrate target.

https://copilot.microsoft.com/shares/zu9NFBVqejw9JFQLR957e

