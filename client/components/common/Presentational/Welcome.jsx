import Link from "next/link";

function Welcome() {
  return (
    <div>
      <Link href="/auth/signin">
        <a>Log In</a>
      </Link>
      <Link href="/account/register">
        <a>Register</a>
      </Link>
    </div>
  );
}

export default Welcome;
