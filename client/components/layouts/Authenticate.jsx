import Image from "next/image";
import Head from "next/head";
import { signIn } from "next-auth/client";

import styles from "../../styles/scss/components/layouts/Authenticate.module.scss";

function Authenticate({ providers, csrfToken }) {
  return (
    <div className="grid place-items-center">
      <Head>
        <title>Authenticate</title>
      </Head>
      <div className="m-10">
        {" "}
        <Image
          src="/images/vm-logo-black.svg"
          width={400}
          height={400}
          objectFit="contain"
        />
      </div>

      <form method="post" action="/api/auth/callback/credentials/">
        <input name="csrfToken" type="hidden" defaultValue={csrfToken} />
        <label>
          Username
          <input className="p-3 m-3" name="username" type="text" />
        </label>
        <label>
          Password
          <input className="p-3 m-3" name="password" type="password" />
        </label>
        <button
          className="p-3 m-3 text-center text-white bg-blue-500 rounded cursor-pointer"
          type="submit"
        >
          Sign in with Tasker
        </button>
      </form>

      <button
        onClick={() => signIn(providers.google.id)}
        className="p-5 text-center text-white bg-red-500 rounded-full cursor-pointer"
      >
        Log in with Google
      </button>
    </div>
  );
}
export default Authenticate;
