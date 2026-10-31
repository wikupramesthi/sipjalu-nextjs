const BUBBLES = Array.from({ length: 16 }, (_, index) => index);

/** Lightweight decorative bubbles rising behind the hero statistics. */
export function FloatingBubbles() {
  return (
    <div className="sj-bubble-field" aria-hidden="true">
      {BUBBLES.map((bubble) => (
        <span className="sj-bubble" key={bubble} />
      ))}
    </div>
  );
}
